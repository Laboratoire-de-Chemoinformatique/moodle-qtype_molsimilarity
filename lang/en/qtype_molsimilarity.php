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
 * Strings for component 'qtype_molsimilarity', language 'en'
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['answer'] = 'Answer: {$a}';
$string['pluginname'] = 'Molsimilarity';
$string['pluginname_help'] = 'In response to a question, the student may draw a molecule to be compared with the answer provided.';
$string['pluginname_link'] = 'question/type/molsimilarity';
$string['pluginnameadding'] = 'Adding a mol similarity question';
$string['pluginnameediting'] = 'Editing a mol similarity question';
$string['pluginnamesummary'] = 'A mol similarity question type that allows the quantitative comparison between the answer given by the student and the one given by the teacher.';
$string['pleaseenterananswer'] = 'Please enter an answer';
$string['answernumber'] = 'Answer: {$a}';
$string['correctansweris'] = 'The correct answer is: ';
$string['correctanswers'] = 'Correct answers';
$string['insertfromeditor'] = 'Insert given structure as answer / update the answer with the structure';
$string['insertfrom'] = 'Answer\'s insertion';
$string['insertfrom_help'] = 'Draw a structure, and click on the \'Insert given structure as answer / update the answer with the structure\' button to insert it as answer. The \'View structure in the editor\' button can be used to check what is the structure stored for each answer. Click on the \'Clear the answer\' button and set the Grade to "None" to remove an answer.';
$string['inserttoeditor'] = 'View structure in the editor';
$string['clearanswer'] = 'Clear the answer';
$string['filloutoneanswer'] = 'You must provide at least one possible answer. Please draw a molecule and click on the "Insert given structure as answer/..." button for each answer. ';
$string['isidaurl'] = 'ISIDA Server Url';
$string['isidaurl_desc'] = 'External marking server ISIDA url to evaluate question answer';
$string['caseno'] = 'Stereo must not be taken in account';
$string['caseyes'] = 'Stereo must be taken in account';
$string['stereoselection'] = 'Option stereochemistry';
$string['moleculeempty'] = 'Please insert a molecule';
$string['privacy:metadata'] = 'The molsimilarity question type plugin does not store any personal data.';
$string['isidaKEY'] = 'ISIDA Server KEY';
$string['isidaKEY_desc'] = 'Key needed to access the external ISIDA marking server.';
$string['errorintestwhilegconnection'] = 'Error while testing the connection: ';
$string['testerrormessage'] = 'Error message : {$a}. ';
$string['testerrorcode'] = 'Error code : {$a}. ';
$string['connectiontestresult'] = 'Result of the connection test: ';
$string['connection-success'] = 'The connection has been successfully established. ';
$string['testconnection'] = 'Test the connection to the Molsimilarity Api.';
$string['settings'] = 'Molsimilarity setting';
$string['messageprovider:molsimilarity_down'] = 'Notify that the correction server is down';
$string['mailmsg'] = 'Molsimilarity question type plugin server is down, please relaunch it. Curl error no: {$a->errno}. Curl error msg: {$a->error}';
$string['mailsubj'] = 'Need to relaunch Molsimilarity question type server';
$string['mailmsg_isidaerror'] = 'Molsimilarity question type plugin server return an error<br> : {$a}';
$string['mailsubj_isidaerror'] = 'Molsimilarity question type server return an error';
$string['messageprovider:molsimilarity_security'] = 'Notify that there is an unauthorized attempt to access the server of correction ';
$string['mailsubj_security'] = 'Unauthorized attempt to connect to molsimilarity correction server. ';
$string['mailmsg_security'] = 'Someone is trying to access molsimilarity correction server. Answer from the server: {$a}';
$string['threshold'] = "Please select a value of threshold. The answer is refused below this threshold.";
$string['threshold_help'] = "If the grade to the power of alpha is superior or equal to the threshold, the grade is equal to the grade to the power of alpha. If not, the grade is equal to 0. ";
$string['alphaselection'] = "Please select a value of alpha value. It will be used to modify the grade accordingly.";
$string['alphaselection_help'] = "If alpha is equal to 1, the score is not modified. If it's superior to 1, the notation is more severe. If it's inferior to 1, the notation is less severe.";
$string['formtest'] = 'Preview answer: ';
$string['scaffoldselection'] = "You can insert a scaffold for the student.";
$string['scaffoldselection_help'] = "A scaffold will be inserted in the student's sketcher to help him.";
