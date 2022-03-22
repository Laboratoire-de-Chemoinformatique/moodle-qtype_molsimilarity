<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Question definition class for the Molsimilarity question type
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/questionbase.php');
require_once($CFG->dirroot . '/question/behaviour/asyncdeferredfeedback/behaviour.php');

/**
 * Represents a molsimilarity question.
 *
 * @copyright  2021 PLYER Louis (louis.plyer@unistra.fr)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class qtype_molsimilarity_question extends question_graded_automatically implements question_async_automatically_gradable {

    public $stereobool;
    public $alpha;
    public $threshold;
    public $answers = array();
    /**
     * @param $CFG
     * @param $SITE
     * @param curl $curl
     * @return void
     * @throws coding_exception
     */
    private static function notify_error(curl $curl, $mailsubject, $errormessage) {
        global $CFG, $SITE;
        $eventdata = new \core\message\message();
        $eventdata->component = 'qtype_molsimilarity';
        $eventdata->name = 'molsimilarity_down';
        $eventdata->userfrom = core_user::get_noreply_user();
        $eventdata->subject = $mailsubject;
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->notification = '1';
        $eventdata->contexturl = $CFG->wwwroot;
        $eventdata->contexturlname = $SITE->fullname;
        $eventdata->replyto = core_user::get_noreply_user()->email;
        $eventdata->fullmessage = $errormessage;
        $eventdata->courseid = SITEID;
        $eventdata->fullmessagehtml = str_replace('\n', '<br/>', $eventdata->fullmessage);

        $recip = get_admins();
        foreach ($recip as $admin) {
            $eventdata->userto = $admin;
            $result = message_send($eventdata);
        }
    }

    /**
     * What data may be included in the form submission when a student submits
     * this question in its current state?
     *
     * This information is used in calls to optional_param. The parameter name
     * has {@link question_attempt::get_field_prefix()} automatically prepended.
     *
     * @return array|string variable name => PARAM_... constant, or, as a special case
     *      that should only be used in unavoidable, the constant question_attempt::USE_RAW_DATA
     *      meaning take all the raw submitted data belonging to this question.
     */
    public function get_expected_data() {
        return array('answer' => PARAM_RAW);
    }

    /**
     * What data would need to be submitted to get this question correct.
     * If there is more than one correct answer, this method should just
     * return one possibility. If it is not possible to compute a correct
     * response, this method should return null.
     *
     * @return array|null parameter name => value.
     */

    public function get_correct_response() {
        $answer = $this->get_correct_answer();
        if (!$answer) {
            return array();
        }
        return array('answer' => $answer->answer);
    }

    public function get_correct_answer() {
        foreach ($this->answers as $answer) {
            $state = question_state::graded_state_for_fraction($answer->fraction);
            if ($state == question_state::$gradedright) {
                return $answer;
            }
        }
        return null;
    }

    /**
     * Used to work out whether the question attempt should move to the COMPLETE or INCOMPLETE state.
     *
     * @param array $response responses, as returned by
     *      {@link question_attempt_step::get_qt_data()}.
     * @return bool whether this response is a complete answer to this question.
     */
    public function is_complete_response(array $response): bool {
        return array_key_exists('answer', $response) &&
                ($response['answer'] !== '');
    }

    /**
     * Use by many of the behaviours to determine whether the student's
     * response has changed. This is normally used to determine that a new set
     * of responses can safely be discarded.
     *
     * @param array $prevresponse the responses previously recorded for this question,
     *      as returned by {@link question_attempt_step::get_qt_data()}
     * @param array $newresponse the new responses, in the same format.
     * @return bool whether the two sets of responses are the same - that is
     *      whether the new set of responses can safely be discarded.
     */
    public function is_same_response(array $prevresponse, array $newresponse) {
        return question_utils::arrays_same_at_key_missing_is_blank(
                $prevresponse, $newresponse, 'answer');
    }

    /**
     * Produce a plain text summary of a response.
     *
     * @param array $response a response, as might be passed to {@link grade_response()}.
     * @return string a plain text summary of that response, that could be used in reports.
     */
    public function summarise_response(array $response) {
        if (isset($response['answer'])) {
            $decoded = json_decode($response['answer']);
            return $decoded->{"mol_file"};
        } else {
            return null;
        }
    }

    /**
     * In situations where is_gradable_response() returns false, this method
     * should generate a description of what the problem is.
     *
     * @param array $response
     * @return string the message.
     */
    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseenterananswer', 'qtype_molsimilarity');
    }

    /**
     * Grade a response to the question, returning a fraction between
     * get_min_fraction() and get_max_fraction(), and the corresponding {@link question_state}
     * right, partial or wrong.
     *
     * @param array $response responses, as returned by
     *      {@link question_attempt_step::get_qt_data()}.
     * @return array (float, integer) the fraction, and the state.
     */

    public function grade_response(array $response) {
        $decodedresponse = json_decode($response['answer']);
        if ($decodedresponse) {
            $grade = $this->compare_mol($decodedresponse->{"mol_file"});
            if ($grade === false) { // F compare_answer returns false if the server is down => question_state sets to needsgrading.
                return array($grade, question_state::$needsgrading);
            } else {
                $grade = pow($grade, $this->alpha); // Alpha parameter used to induce a change on grade value.
                if ($grade >= $this->threshold) { // Check if the grade is superior than the threshold.
                    return array($grade, question_state::graded_state_for_fraction($grade));
                } else {
                    $grade = 0;
                    return array($grade, question_state::graded_state_for_fraction($grade));
                }
            }
        } else {
            return array(0, question_state::$gradedwrong);
        }
    }
    // Ref : https://github.com/NouvelleTechno/JWT-en-PHP/blob/8d0d64ff58a19aa8ca9a5c54b1b73c80edceb6dc/classes/JWT.php#L12 .
    public function generate(array $payload, string $secret, int $validity = 86400): string {
        if ($validity > 0) {
            $now = new \DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }
        $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
        ];

        // Encoded in base64.
        $base64header = base64_encode(json_encode($header));
        $base64payload = base64_encode(json_encode($payload));

        // Cleaning the encoded values.
        // The +, / and = are removed.
        $base64header = str_replace(['+', '/', '='], ['-', '_', ''], $base64header);
        $base64payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64payload);

        // Generating the signature.
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64header . '.' . $base64payload, $secret, true);

        $base64signature = base64_encode($signature);

        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64signature);

        // Token created.
        $jwt = $base64header . '.' . $base64payload . '.' . $signature;

        return $jwt;
    }

    /**
     * Prepare the json to be sent using call_API(), then return the grade given by call_API().
     *
     * @param $response
     * @return bool|mixed|string
     * @throws coding_exception
     */
    public function compare_mol ($response) {
        // Create the JSON file to be send to the API.
        // Prepare the Multidimensional Associative Array.
        $singlearray['student'] = array("mol" => $response);
        $i = 1;
        $correction = [];
        foreach ($this->answers as $answer) {
            $correction["mol_". $i] = json_decode($answer->answer)->{"mol_file"};
            $i++;
        }
        $singlearray['correction'] = $correction;
        $singlearray['stereoopt'] = array("opt" => $this->stereobool);
        $singlearray['corectopt'] = array("nbmol" => count($this->answers));
        try {
            $attemptid = required_param('attempt', PARAM_INT);
        } catch (Exception $exception) {  // Used for overview mode.
            $attemptid = random_int(1, 100);
        }

        $uniqueid = $attemptid . "-" . $this->id; // To have a unique id when we write a file in the API.
        $singlearray['attemptid'] = array("id" => $uniqueid);

        // Prepare a JWT using the private KEY.
        $token = self::generate($singlearray, get_config('qtype_molsimilarity', 'isidaKEY'));
        // Prepare the Json to be given to the API.
        $jsonified = json_encode($singlearray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Call to the API.
        $apiresponse = self::call_api($jsonified, $token);

        // Once the API answers, strip the JSON to get the grade. TODO Check the error num and send it to admin.
        if (!isset(json_decode($apiresponse, true)['student']['grade'])) {
            return false;
        } else {
            $grade = json_decode($apiresponse, true)['student']['grade'];
            if (gettype($grade) == "integer" || "double") {
                return $grade;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $jsondata false|string containing the student, correction and stereo information.
     * @param $token string containing the JWT token to identify the connection to the API
     * @return bool|string the grade contained in the json
     * @throws dml_exception
     */
    public static function call_api($jsondata, $token) {
        global $SITE, $CFG;
        $curl = new curl();
        $isidaurl = get_config('qtype_molsimilarity', 'isidaurl');
        $option = array(
                'returntransfer' => true,
                'httpheader' => array("Authorization: Bearer " . $token)
        );
        $result = $curl->post($isidaurl . "/isida", $jsondata, $option);
        if ($curl->error) {

            // If there is an error, we send a Moodle notif to the admins to reboot the api server.
            self::notify_error( $curl, get_string('mailsubj', 'qtype_molsimilarity'),
                get_string('mailmsg', 'qtype_molsimilarity', $curl));
            return $curl->errno;
        } else if ($curl->get_info()['http_code'] == 401) {
            self::notify_error( $curl, get_string('mailsubj_security', 'qtype_molsimilarity'),
                get_string('mailmsg_security', 'qtype_molsimilarity',
                    json_decode($result, true)['reason']));
        } else if (isset(json_decode($result, true)['success'])
            && (json_decode($result, true)['success']) == 'False') {
            // If there is an error, we send a Moodle notif to the admins to reboot the api server.
            self::notify_error( $curl, get_string('mailsubj_security', 'qtype_molsimilarity'),
                get_string('mailmsg_security', 'qtype_molsimilarity',
                    json_decode($result, true)['reason']));
            return $result;
        } else {
                return $result;
        }
    }

    // Implement question_async_automatically_gradable.

    public function make_behaviour(question_attempt $qa, $preferredbehaviour) {
        if(get_config('qtype_molsimilarity','asyncbehaviour')) {
            return question_engine::make_behaviour('asyncdeferredfeedback', $qa, $preferredbehaviour);
        }
        else return parent::make_behaviour($qa, $preferredbehaviour);
    }

    public function launch_async_call($qaid, $userid, $answer, $extrainfos) {
        global $CFG;
        $curl = new curl();
        $remoteurl = get_config('qtype_molsimilarity', 'serverurl');
        $servertoken = get_config('qtype_molsimilarity', 'moodlewstoken');
        // Create the JSON file to be send to the API.
        // Prepare the Multidimensional Associative Array.
        $decodedresponse = json_decode($answer);
        $singlearray['student'] = array("mol" => $decodedresponse->{"mol_file"});
        $i = 1;
        $correction = [];
        foreach ($this->answers as $ranswer) {
            $correction["mol_". $i] = json_decode($ranswer->answer)->{"mol_file"};
            $i++;
        }
        $singlearray['correction'] = $correction;
        $singlearray['stereoopt'] = array("opt" => $this->stereobool);
        $singlearray['corectopt'] = array("nbmol" => count($this->answers));

        $uniqueid = $qaid . "-" . $this->id; // To have a unique id when we write a file in the API.
        $singlearray['attemptid'] = array("id" => $uniqueid);

        // Prepare a JWT using the private KEY.
        $token = self::generate($singlearray, get_config('qtype_molsimilarity', 'isidaKEY'));
        // Prepare the Json to be given to the API.
        $jsonified = json_encode($singlearray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $extrainfosarray = json_decode($extrainfos);
        $extrainfosarray['jsonified'] = $jsonified;
        $extrainfos = json_encode($extrainfosarray);
        $postdatas =
            array(
                'userid' => $userid,
                'rightanswers' => json_encode($this->answers),
                'questionattemptid' => $qaid,
                'answer' => $answer,
                'uuid' => get_config('qtype_molsimilarity', 'moodleid'),
                'qtype' => 'qtype_molsimilarity',
                'token' => $servertoken,
                'extrainfos' => $extrainfos,
            );
        if(!empty($CFG->xdebug)){
            $curl->setopt(array('CURLOPT_COOKIE' => 'XDEBUG_SESSION=PHPSTORM;path=/;'));
        }
        $result = $curl->post($remoteurl , $postdatas);
        $errno = $curl->get_errno();
        $httpcode = $curl->get_info()['http_code'];
        if($errno >0){
            return array(false,$errno, $result);
        } else if($httpcode === 404) {
            return array(false, 404, "$remoteurl url not found");
        }
        return array($result, null, null);
    }

    public function is_async_gradable_response(array $submitteddatas) {
        return array_key_exists('fraction', $submitteddatas);
    }

    public function async_grade_response($datas) {
        $grade = $datas['fraction'];
        if ($grade === false) { // F compare_answer returns false if the server is down => question_state sets to needsgrading.
            return array($grade, question_state::$needsgrading);
        } else {
            return array($grade, question_state::graded_state_for_fraction($grade));
        }
    }
    public function get_extras_infos() {
        return '';
    }
}
