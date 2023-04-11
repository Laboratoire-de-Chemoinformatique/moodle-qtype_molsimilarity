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
 * Upgrader code for the Molsimilarity question type
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
 * Upgrade code for the molsimilarity question type.
 * @param int $oldversion the version we are upgrading from.
 */
function xmldb_qtype_molsimilarity_upgrade($oldversion = 0) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();
    if ($oldversion < 2022012405) {
        // Define field id to be added to question_molsimilarity.
        $table = new xmldb_table('question_molsimilarity');

        // Add stereobool parameter.
        $field = new xmldb_field('stereobool', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'questionid');

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Add threshold parameter.
        $field = new xmldb_field('threshold', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '0', 'stereobool');

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Add alpha parameter.
        $field = new xmldb_field('alpha', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '1', 'threshold');

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        // Molsimilarity savepoint reached.
        upgrade_plugin_savepoint(true, 2022012405, 'qtype', 'molsimilarity');
    }

    if ($oldversion < 2023041100) {
        $table = new xmldb_table('question_molsimilarity');

        // Add scaffold parameter.
        $field = new xmldb_field('scaffold', XMLDB_TYPE_TEXT);

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2023041100, 'qtype', 'molsimilarity');

    }

    return true;
}
