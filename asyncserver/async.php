<?php

require_once('../../../../config.php');
require_once($CFG->libdir.'/filelib.php');;

require_once($CFG->dirroot.'/question/type/molsimilarity/question.php');


$questionid = required_param('questionid', PARAM_INT);
$questionattemptid = required_param('questionattemptid', PARAM_INT);
$userid = required_param('userid', PARAM_INT);
$answer = required_param('answer', PARAM_TEXT);
$extrainfos = required_param('extrainfos', PARAM_TEXT);
$token = required_param('token', PARAM_TEXT);
// rightanswers not necessary here since included in jsonified
$rightanswers = required_param('rightanswers', PARAM_TEXT);
// No need to use moodleid in that context, moodleid is for remote server that work for various moodle
// servertoken not used here
$moodletoken = get_config('qtype_molsimilarity', 'moodlewstoken');

// Call isida
$extrainfosarr = (array)json_decode($extrainfos);
$jsonified = $extrainfosarr['jsonified'];
unset($extrainfosarr['jsonified']);
$extrainfos = json_encode($extrainfosarr);
$apiresponse = qtype_molsimilarity_question::call_api($jsonified, $token);
$grade = -1;
if (array_key_exists('grade', json_decode($apiresponse, true)['student'])) {
    $grade = json_decode($apiresponse, true)['student']['grade'];
    if (gettype($grade) != "integer" && gettype($grade) != "double") {
        $grade = -1;
    }
}

if($grade != -1){

    $curl = new curl();
    $postdatas = array(
        'moodlewsrestformat' => 'json',
        'wsfunction' =>'qbehaviour_asyncdeferredfeedback_grade_with_fraction_evaluation',
        'wstoken' => $moodletoken,
        'questionattemptid' => $questionattemptid,
        'userid' => $userid,
        'fraction' => $grade,
        'answer' => urlencode($answer),
        'extrainfos' => $extrainfos
    );
    $request = "$CFG->wwwroot/webservice/rest/server.php?";
    $i=0;
    foreach($postdatas as $index => $value){
        if($i>0){
            $request.="&";
        }
        $request.="$index=$value";
        $i++;
    }

    $output = array();
    $resultcode = null;
    $cookie='';
    if(!empty($CFG->xdebug)) {
        $cookie = "--cookie 'XDEBUG_SESSION=PHPSTORM;path=/;'";
    }
    $command = "curl ".$cookie." -H 'Content-type: application/json' \"$request\" >/dev/null 2>&1 &";
    exec($command, $output, $resultcode);
    echo "1";
}
echo '0';


