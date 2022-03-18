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
 * Plugin administration for the Molsimilarity question type
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $DB;

// Redefine the molsimilarity admin menu entry to be expandable.
$plugin = core_plugin_manager::instance()->get_plugin_info('qtype_molsimilarity');
$qtypemolsimilarityfolder = new admin_category('qtypemolsimilarityfolder',
        new lang_string('pluginname', 'qtype_molsimilarity'), $plugin->is_enabled() === false);

// Add the Settings admin menu entry.
$ADMIN->add('qtypesettings', $qtypemolsimilarityfolder);
$settings->visiblename = new lang_string('settings', 'qtype_molsimilarity');

// Add the Libraries admin menu entry.
$ADMIN->add('qtypemolsimilarityfolder', $settings);

$ADMIN->add('qtypemolsimilarityfolder', new admin_externalpage('molsimilarityconnectiontest',
        new lang_string('testconnection', 'qtype_molsimilarity'),
        new moodle_url('/question/type/molsimilarity/test_connection.php')));

if ($hassiteconfig) {
    $settings->add(
            new admin_setting_configtext('qtype_molsimilarity/isidaurl',
                    get_string('isidaurl', 'qtype_molsimilarity'),
                    get_string('isidaurl_desc', 'qtype_molsimilarity'),
                    'localhost:9080')
    );
    $settings->add(
            new admin_setting_configtext('qtype_molsimilarity/isidaKEY',
                    get_string('isidaKEY', 'qtype_molsimilarity'),
                    get_string('isidaKEY_desc', 'qtype_molsimilarity'),
                    'PutYourOwnKeyHere')
    );
    $settings->add(
            new qtype_molsimilarity_test_connection('qtype_molsimilarity/testconnectionout', '', '')
    );
    // async deferred feedback necessary settings
    $settings->add(
        new admin_setting_configcheckbox('qtype_molsimilarity/asyncbehaviour',
            get_string('asyncbehaviour', 'qtype_molsimilarity'),
            get_string('asyncbehaviour_desc', 'qtype_molsimilarity'),
            0
        )
    );
    $settings->add(
        new admin_setting_configtext('qtype_molsimilarity/serverurl',
            get_string('serverurl', 'qtype_molsimilarity'),
            get_string('serverurl_desc', 'qtype_molsimilarity'),
            $CFG->wwwroot.'/question/type/qtype_molsimilarity/server/async.php')
    );
    $settings->add(
        new admin_setting_configtext('qtype_molsimilarity/servertoken',
            get_string('servertoken', 'qtype_molsimilarity'),
            get_string('servertoken_desc', 'qtype_molsimilarity'),
            '')
    );

    $settings->add(
        new admin_setting_configtext('qtype_molsimilarity/moodleid',
            get_string('moodleid', 'qtype_molsimilarity'),
            get_string('moodleid_desc', 'qtype_molsimilarity'),1)
    );
    $settings->add(
        new admin_setting_configtext('qtype_molsimilarity/moodlewstoken',
            get_string('moodlewstoken', 'qtype_molsimilarity'),
            get_string('moodlewstoken_desc', 'qtype_molsimilarity'),
            '167d822a2f498261ebc655e6b79839a6')
    );
}
$settings = null; // Prevent Moodle from adding settings block in standard location.
