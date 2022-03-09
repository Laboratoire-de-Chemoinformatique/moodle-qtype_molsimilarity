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
 * External Web Service for the Molsimilarity question type
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

class qtype_molsimilarity_external extends external_api
{
    public static function modify_molfile_parameters() {
        return new external_function_parameters(
                array(
                    'molfile' => new external_value(PARAM_TEXT, 'molfile', VALUE_REQUIRED),
                    'json_data' => new external_value(PARAM_TEXT,
                        'json_data containing info about the lone pairs and radicals',
                        VALUE_REQUIRED),
                    'sesskey' => new external_value(PARAM_ALPHANUMEXT, 'sesskey', VALUE_REQUIRED)
                )
        );
    }
    // From 2nd comment https://www.php.net/manual/fr/function.array-column.php .
    private static function molsimilarity_array_column_ext($array, $columnkey, $indexkey = null): array {
        $result = array();
        foreach ($array as $subarray => $value) {
            if (array_key_exists($columnkey, $value)) {
                $val = $array[$subarray][$columnkey];
            } else if ($columnkey === null) {
                $val = $value;
            } else {
                continue;
            }

            if ($indexkey === null) {
                $result[] = $val;
            } else if ($indexkey == -1 || array_key_exists($indexkey, $value)) {
                $result[($indexkey == -1) ? $subarray : $array[$subarray][$indexkey]] = $val;
            }
        }
        return $result;
    }

    public static function modify_molfile($molfile, $jsondata, $sesskey) {
        $params = self::validate_parameters(self::modify_molfile_parameters(),
                    array('molfile' => $molfile, 'json_data' => $jsondata, 'sesskey' => $sesskey));

        // We look for the number of lone pairs and radicals in the initial json.

        $decoded = json_decode($jsondata, true);
        $atominf = $decoded['m'][0]['a'];
        $lonepairs = self::molsimilarity_array_column_ext($atominf, 'p', -1);
        $radicals = self::molsimilarity_array_column_ext($atominf, 'r', -1);

        if (!$lonepairs & !$radicals) {
            $finalmol = $molfile;
        } else {

            // Make a list of lines.
            $lines = explode("\n", $molfile);
            // Get the count line and take the numbers from it.
            $countline = $lines[3];
            preg_match_all('!\d+!', $countline, $numbers);
            $nbatom = $numbers[0][0];
            $nbbonds = $numbers[0][1];
            // Get the different parts of the molfile.
            $header = array_slice($lines, 0, 3);
            $atomblock = array_slice($lines, 4, $nbatom);
            $bondblock = array_slice($lines, (4 + $nbatom), $nbbonds);
            $endfile = array_slice($lines, -1, 1);

            if ($lonepairs) {

                // Get the initial number of atoms and bonds.
                $nblonepairs = 0;
                $nbatomfinal = $nbatom;
                $nbbondsfinal = $nbbonds;
                $bondvanilla = '  1  0  0  0  0';

                foreach ($lonepairs as $key => $value) {
                    $numatom = $key + 1;
                    // Creating a line for the atom block.
                    $atomline = $atomblock[$key];
                    $atomline[31] = 'L';
                    $atomline[32] = 'P';
                    for ($i = 1; $i <= $value; $i++) {
                        $atomblock[$nbatomfinal] = $atomline;
                        $nbatomfinal++;

                        // Creating a line for the bond block.
                        $bondline = '';
                        $bondline .= sprintf("% 3d", $numatom);
                        $bondline .= sprintf("% 3d", $nbatomfinal);
                        $bondline .= $bondvanilla;
                        $bondblock[$nbbondsfinal] = $bondline;
                        $nblonepairs++;
                        $nbbondsfinal++;

                    }
                }
                // Change number of atoms and bonds to new one.
                $countlineedited = '';
                $countlineedited .= sprintf("% 3d", $nbatomfinal);
                $countlineedited .= sprintf("% 3d", $nbbondsfinal);
                $countlineedited .= substr($countline, 6);
                $countlinearray = explode('\n', $countlineedited);
            }

            if ($radicals) {
                $nbradicals = 0;
                $radline = 'M  RAD';
                $endradline = '';
                foreach ($radicals as $key => $value) {
                    $numatom = $key + 1;
                    $endradline .= sprintf("% 3d", $numatom);
                    $endradline .= sprintf("% 3d", 2);
                    $nbradicals ++;
                }
                $radline .= sprintf("% 3d", $nbradicals);
                $radline .= $endradline;
                $radlinearray = explode('\n', $radline);
                if (!$lonepairs) {
                    $countlinearray = explode('\n', $countline);
                }
            }

            // Rewrite the molfile.
            $finalmol = array();
            if ($radicals) {
                $finalmol = implode("\n", array_merge($finalmol, $header, $countlinearray,
                        $atomblock, $bondblock, $radlinearray, $endfile));
            } else {
                $finalmol = implode("\n", array_merge($finalmol, $header, $countlinearray, $atomblock, $bondblock, $endfile));
            }
        }
        // A json is sent back including the json and the new molfile.
        $endmol = ['json' => $jsondata, 'mol_file' => $finalmol];
        return $endmol;

    }
    public static function modify_molfile_returns() {
        return new external_single_structure(
                array(
                        'json'   => new external_value(PARAM_RAW, 'Backup Status'),
                        'mol_file' => new external_value(PARAM_RAW, 'Backup progress'),
                ), 'Backup completion status'
        );
    }
}
