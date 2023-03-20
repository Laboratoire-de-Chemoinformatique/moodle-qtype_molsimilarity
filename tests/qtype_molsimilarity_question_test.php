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
 * Unit tests for the molsimilarity question definition class.
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/type/questionbase.php');
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');
require_once($CFG->dirroot . '/question/type/molsimilarity/question.php');
/**
 * Unit tests for the molsimilarity question definition class.
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_molsimilarity_question_test extends advanced_testcase {

    protected function setUp(): void {
        $this->resetAfterTest();
        parent::setUp();
        $this->set_isida_url();
    }

    private function set_isida_url(): void {
        global $CFG;
        $configtestfile = $CFG->dirroot . '/question/type/molsimilarity/config-test.php';
        if( file_exists($configtestfile)) {
            require($configtestfile);
        }
    }

    public function test_get_expected_data() {

        $question = test_question_maker::make_question('molsimilarity', 'ethanollp');
        $this->assertEquals(array('answer' => PARAM_RAW), $question->get_expected_data());
    }

    public function test_is_complete_response() {
        $question = test_question_maker::make_question('molsimilarity', 'ethanollp');
        $this->assertFalse($question->is_complete_response(array()));
        $this->assertFalse($question->is_complete_response(array('answer' => '')));
        $this->assertTrue($question->is_complete_response(array('answer' => '{}')));
    }

    public function test_get_correct_response() {
        $question = test_question_maker::make_question('molsimilarity', 'ethanollp');

        $answer = '{"json":"{\"m\":[{\"a\":[{\"x\":230.17949168589269,\"y\":151.89999999999998,\"i\":\"a0\",\"l\":\"O\",\"p\":2},';
        $answer .= '{\"x\":247.49999976158148,\"y\":141.89999999999998,\"i\":\"a1\"},{\"x\":264.8205078372702,';
        $answer .= '\"y\":151.9,\"i\":\"a2\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}",';
        $answer .= '"mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n';
        $answer .= '  5  4  0  0  0  0            999 V2000\n   -0.8660   -0.2500    0.0000 O   0  0  0  0  0  0\n';
        $answer .= '    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n';
        $answer .= '   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n';
        $answer .= '  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\n  1  4  1  0  0  0  0\n  1  5  1  0  0  0  0\nM  END"}';
        $this->assertEquals(array('answer' => $answer), $question->get_correct_response());
    }

    public function test_get_question_summary() {
        $sa = test_question_maker::make_question('molsimilarity', 'ethanollp');
        $qsummary = $sa->get_question_summary();
        $this->assertEquals('Draw the lewis structure of a molecule of ethanol.', $qsummary);
    }

    public function test_summarise_response() {
        $sa = test_question_maker::make_question('molsimilarity', 'ethanollp');

        $answer = '{"json":"{\"m\":[{\"a\":[{\"x\":230.17949168589269,\"y\":151.89999999999998,\"i\":\"a0\",\"l\":\"O\",\"p\":2},';
        $answer .= '{\"x\":247.49999976158148,\"y\":141.89999999999998,\"i\":\"a1\"},{\"x\":264.8205078372702,';
        $answer .= '\"y\":151.9,\"i\":\"a2\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}",';
        $answer .= '"mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n';
        $answer .= '  5  4  0  0  0  0            999 V2000\n   -0.8660   -0.2500    0.0000 O   0  0  0  0  0  0\n';
        $answer .= '    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n';
        $answer .= '   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n';
        $answer .= '  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\n  1  4  1  0  0  0  0\n  1  5  1  0  0  0  0\nM  END"}';
        $summary = $sa->summarise_response(array('answer' => $answer));
        $molfile = json_decode($answer)->{"mol_file"};
        $this->assertEquals($molfile, $summary);
    }

    public function test_get_validation_error() {
        $question = test_question_maker::make_question('molsimilarity', 'ethanollp');
        $this->assertEquals(get_string('pleaseenterananswer', 'qtype_molsimilarity'),
            $question->get_validation_error(array()));
        $this->assertNotEquals(get_string('pleaseenterananswer', 'qtype_molsimilarity'),
            $question->get_validation_error(array('answer' => '{}')));
    }

    public function test_compute_final_grade() {
        $sa = test_question_maker::make_question('molsimilarity', 'ethanollp');
        $sa->start_attempt(new question_attempt_step(), 1); // Needed ?
        $this->assertEquals(array(1, question_state::graded_state_for_fraction(1)),
            $sa->grade_response($sa->get_correct_response()));
        $nulmol = '{"json":"{\"m\":[{\"a\":[]}]}","mol_file":"Molecule from ChemDoodle Web Components\n';
        $nulmol .= '\nhttp://www.ichemlabs.com\n  0  0  0  0  0  0            999 V2000\nM  END"}';
        $molnolp = '{"json":"{\"m\":[{\"a\":[{\"x\":260.75,\"y\":169,\"i\":\"a0\",\"l\":\"O\"},';
        $molnolp .= '{\"x\":278.0705080756888,\"y\":159,\"i\":\"a1\"},{\"x\":295.3910161513775,\"y\":169.00000000000003,\"i\":\"a2';
        $molnolp .= '\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}","mol_file":"Molecule from';
        $molnolp .= ' ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  3  2  0  0  0  0            999 V2000\n   -0.8660';
        $molnolp .= '   -0.2500    0.0000 O   0  0  0  0  0  0\n    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n';
        $molnolp .= '    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\nM  END"}';
        $this->assertEquals(array(0, question_state::graded_state_for_fraction(0)),
            $sa->grade_response(array('answer' => $nulmol)));
        $this->assertNotEquals(array(1, question_state::graded_state_for_fraction(1)),
            $sa->grade_response(array('answer' => $nulmol)));
        $this->assertNotEquals(array(0, question_state::graded_state_for_fraction(0)),
            $sa->grade_response(array('answer' => $molnolp)));
        $this->assertNotEquals(array(1, question_state::graded_state_for_fraction(1)),
            $sa->grade_response(array('answer' => $molnolp)));
    }

    public function test_compute_final_grade_stereo() {
        $sa = test_question_maker::make_question('molsimilarity', 's1aminoethanol');
        $sa->start_attempt(new question_attempt_step(), 1);
        $nulmol = '{"json":"{\"m\":[{\"a\":[]}]}","mol_file":"Molecule from ChemDoodle Web Components\n';
        $nulmol .= '\nhttp://www.ichemlabs.com\n  0  0  0  0  0  0            999 V2000\nM  END"}';

        $r1aminoethanol = '{"json":"{\"m\":[{\"a\":[{\"x\":221.75,\"y\":156,\"i\":\"a0\",\"l\":\"N\"},{\"x\":239.07050807568876,';
        $r1aminoethanol .= '\"y\":146,\"i\":\"a1\"},{\"x\":256.3910161513775,\"y\":156,\"i\":\"a2\"},{\"x\":239.07050807568876,';
        $r1aminoethanol .= '\"y\":126,\"i\":\"a3\",\"l\":\"O\"},{\"x\":259.07050807568874,\"y\":146,\"i\":\"a4\",\"l\":\"H\"}],';
        $r1aminoethanol .= '\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\",\"s\":\"recessed\"},{\"b\":1,\"e';
        $r1aminoethanol .= '\":3,\"i\":\"b2\"},{\"b\":1,\"e\":4,\"i\":\"b3\",\"s\":\"protruding\"}]}]}","mol_file":"';
        $r1aminoethanol .= 'Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  5  4  0  0  0  0            ';
        $r1aminoethanol .= '999 V2000\n   -0.9330   -0.7500    0.0000 N   0  0  0  0  0  0\n   -0.0670   -0.2500    0.0000 C   ';
        $r1aminoethanol .= '0  0  0  0  0  0\n    0.7990   -0.7500    0.0000 C   0  0  0  0  0  0\n   -0.0670    0.7500    0.0000';
        $r1aminoethanol .= ' O   0  0  0  0  0  0\n    0.9330   -0.2500    0.0000 H   0  0  0  0  0  0\n  1  2  1  0  0  0  0\n  ';
        $r1aminoethanol .= '2  3  1  6  0  0  0\n  2  4  1  0  0  0  0\n  2  5  1  1  0  0  0\nM  END"}';
        $this->assertEquals(array(1, question_state::graded_state_for_fraction(1)),
            $sa->grade_response($sa->get_correct_response()));
        $this->assertEquals(array(0, question_state::graded_state_for_fraction(0)),
            $sa->grade_response(array('answer' => $r1aminoethanol)));
        $this->assertEquals(array(0, question_state::graded_state_for_fraction(0)),
            $sa->grade_response(array('answer' => $nulmol)));
    }
}
