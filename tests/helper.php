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
 * Test helpers for the molsimilarity question type.
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Test helper class for the molsimilarity question type.
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_molsimilarity_test_helper extends question_test_helper {
    public function get_test_questions() {
        return array('ethanollp', 's1aminoethanol');
    }

    public function make_molsimilarity_question_ethanollp() {
        question_bank::load_question_definition_classes('molsimilarity');
        $sa = new qtype_molsimilarity_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Molsimilarity question';
        $sa->questiontext = 'Draw the lewis structure of a molecule of ethanol.';
        $sa->stereobool = 0;
        $sa->threshold = 0;
        $sa->alpha = 1;
        $sa->scaffold = '';
        $sa->generalfeedback = 'Generalfeedback: ethanol should not be confused with methanol.';
        $answer = '{"json":"{\"m\":[{\"a\":[{\"x\":230.17949168589269,\"y\":151.89999999999998,\"i\":\"a0\",\"l\":\"O\",\"p\":2},';
        $answer .= '{\"x\":247.49999976158148,\"y\":141.89999999999998,\"i\":\"a1\"},{\"x\":264.8205078372702,';
        $answer .= '\"y\":151.9,\"i\":\"a2\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}",';
        $answer .= '"mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n';
        $answer .= '  5  4  0  0  0  0            999 V2000\n   -0.8660   -0.2500    0.0000 O   0  0  0  0  0  0\n';
        $answer .= '    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n';
        $answer .= '   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n';
        $answer .= '  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\n  1  4  1  0  0  0  0\n  1  5  1  0  0  0  0\nM  END"}';
        $sa->answers = array(
               1 => new question_answer(1, $answer, 1.0, 'Think about the lone pairs !', 1)
        );
        $sa->qtype = question_bank::get_qtype('molsimilarity');
        return $sa;
    }

    public function make_molsimilarity_question_s1aminoethanol() {
        question_bank::load_question_definition_classes('molsimilarity');
        $sa = new qtype_molsimilarity_question();
        test_question_maker::initialise_a_question($sa);
        $sa->name = 'Molsimilarity question';
        $sa->questiontext = 'Draw the lewis structure of a molecule of ethanol.';
        $sa->stereobool = 1;
        $sa->threshold = 0;
        $sa->alpha = 1;
        $sa->scaffold = '';
        $sa->generalfeedback = 'Generalfeedback: ethanol should not be confused with methanol.';
        $answer = '{"json":"{\"m\":[{\"a\":[{\"x\":221.75,\"y\":156,\"i\":\"a0\",\"l\":\"N\"},{\"x\":239.07050807568876,\"y\":146,';
        $answer .= '\"i\":\"a1\"},{\"x\":256.3910161513775,\"y\":156,\"i\":\"a2\"},{\"x\":239.07050807568876,\"y\":126,\"i\":\"a3';
        $answer .= '\",\"l\":\"O\"},{\"x\":259.07050807568874,\"y\":146,\"i\":\"a4\",\"l\":\"H\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":';
        $answer .= '\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\",\"s\":\"protruding\"},{\"b\":1,\"e\":3,\"i\":\"b2\"},{\"b\":1,\"e\":4,';
        $answer .= '\"i\":\"b3\",\"s\":\"recessed\"}]}]}","mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.icheml';
        $answer .= 'abs.com\n  5  4  0  0  0  0            999 V2000\n   -0.9330   -0.7500    0.0000 N   0  0  0  0  0  0';
        $answer .= '\n   -0.0670   -0.2500    0.0000 C   0  0  0  0  0  0\n    0.7990   -0.7500    0.0000 C   0  0  0  0  0  0';
        $answer .= '\n   -0.0670    0.7500    0.0000 O   0  0  0  0  0  0\n    0.9330   -0.2500    0.0000 H   0  0  0  0  0  0';
        $answer .= '\n  1  2  1  0  0  0  0\n  2  3  1  1  0  0  0\n  2  4  1  0  0  0  0\n  2  5  1  6  0  0  0\nM  END"}';
        $sa->answers = array(
                1 => new question_answer(1, $answer, 1.0, 'Watchout for the stereochemistry !',
                        1)
        );
        $sa->qtype = question_bank::get_qtype('molsimilarity');
        return $sa;
    }

    public function get_molsimilarity_question_data_ethanollp() {
        $qdata = new stdClass();
        test_question_maker::initialise_question_data($qdata);

        $qdata->qtype = 'molsimilarity';
        $qdata->name = 'Molsimilarity question';
        $qdata->questiontext = 'Draw the lewis structure of a molecule of ethanol.';
        $qdata->generalfeedback = 'Generalfeedback: ethanol should not be confused with methanol.';

        $qdata->options = new stdClass();
        $answer = '{"json":"{\"m\":[{\"a\":[{\"x\":230.17949168589269,\"y\":151.89999999999998,\"i\":\"a0\",\"l\":\"O\",\"p\":2},';
        $answer .= '{\"x\":247.49999976158148,\"y\":141.89999999999998,\"i\":\"a1\"},{\"x\":264.8205078372702,';
        $answer .= '\"y\":151.9,\"i\":\"a2\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}",';
        $answer .= '"mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n';
        $answer .= '  5  4  0  0  0  0            999 V2000\n   -0.8660   -0.2500    0.0000 O   0  0  0  0  0  0\n';
        $answer .= '    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n';
        $answer .= '   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n';
        $answer .= '  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\n  1  4  1  0  0  0  0\n  1  5  1  0  0  0  0\nM  END"}';
        $qdata->options->answers = array(
                1 => new question_answer(1, $answer, 1.0, 'Think about the lone pairs !', 1)
        );
        return $qdata;
    }

    public function get_molsimilarity_question_form_data_ethanollp() {
        $form = new stdClass();

        $form->name = 'Molsimilarity question';
        $form->questiontext = array('text' => 'Draw the lewis structure of a molecule of ethanol.', 'format' => FORMAT_HTML);
        $form->defaultmark = 1.0;
        $form->generalfeedback = array('text' => 'Ethanol should not be confused with methanol.', 'format' => FORMAT_HTML);
        $form->stereobool = 1;
        $form->threshold = 0;
        $form->alpha = 1;
        $form->scaffold = '';
        $answer = '{"json":"{\"m\":[{\"a\":[{\"x\":230.17949168589269,\"y\":151.89999999999998,\"i\":\"a0\",\"l\":\"O\",\"p\":2},';
        $answer .= '{\"x\":247.49999976158148,\"y\":141.89999999999998,\"i\":\"a1\"},{\"x\":264.8205078372702,';
        $answer .= '\"y\":151.9,\"i\":\"a2\"}],\"b\":[{\"b\":0,\"e\":1,\"i\":\"b0\"},{\"b\":1,\"e\":2,\"i\":\"b1\"}]}]}",';
        $answer .= '"mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n';
        $answer .= '  5  4  0  0  0  0            999 V2000\n   -0.8660   -0.2500    0.0000 O   0  0  0  0  0  0\n';
        $answer .= '    0.0000    0.2500    0.0000 C   0  0  0  0  0  0\n    0.8660   -0.2500    0.0000 C   0  0  0  0  0  0\n';
        $answer .= '   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n   -0.8660   -0.2500    0.0000 LP  0  0  0  0  0  0\n';
        $answer .= '  1  2  1  0  0  0  0\n  2  3  1  0  0  0  0\n  1  4  1  0  0  0  0\n  1  5  1  0  0  0  0\nM  END"}';
        $form->answer = array($answer);
        $form->fraction = array('1.0');
        $form->feedback = array(array('text' => 'Think about the lone pairs !', 'format' => FORMAT_HTML));

        return $form;
    }
}
