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
 *
 * @package
 * @subpackage
 * @copyright  2026 Université de Strasbourg  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../../../lib/behat/behat_base.php');
class behat_qtype_molsimilarity extends behat_base {

    /**
     * Create a step to fill in hidden form
     * @Given /^I fill molsimilarity answer field "(?P<fieldcssselector_string>(?:[^"]|\\")*)" with carbon/
     */
    public function iFillMolsimilarityFieldWithCarbon($field)
    {
        //$this->getSession()->getPage()->find('css', $field)->setValue($value);
        $javascript = "document.getElementById('$field').value='"
            .'{"json":"{\"m\":[{\"a\":[{\"x\":275,\"y\":150,\"i\":\"a0\"}]}]}","mol_file":"Molecule from ChemDoodle Web Components\n\nhttp://www.ichemlabs.com\n  1  0  0  0  0  0            999 V2000\n    0.0000    0.0000    0.0000 C   0  0  0  0  0  0\nM  END"}'
            ."'";
        $this->getSession()->executeScript($javascript);
    }

}