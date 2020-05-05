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

defined('MOODLE_INTERNAL') || die();

function create_xapi_log_table($dbman, $tablename) {
    // Define table to be created.
    $table = new xmldb_table($tablename);

    // Adding fields to table.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('eventname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
    $table->add_field('component', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
    $table->add_field('action', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
    $table->add_field('target', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
    $table->add_field('objecttable', XMLDB_TYPE_CHAR, '50', null, null, null, null);
    $table->add_field('objectid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('crud', XMLDB_TYPE_CHAR, '1', null, XMLDB_NOTNULL, null, null);
    $table->add_field('edulevel', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
    $table->add_field('contextid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('contextlevel', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('contextinstanceid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('relateduserid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('anonymous', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('other', XMLDB_TYPE_TEXT, null, null, null, null, null);
    $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('origin', XMLDB_TYPE_CHAR, '10', null, null, null, null);
    $table->add_field('ip', XMLDB_TYPE_CHAR, '45', null, null, null, null);
    $table->add_field('realuserid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

    // Adding keys to table.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Adding indexes to table.
    $table->add_index('timecreated', XMLDB_INDEX_NOTUNIQUE, array('timecreated'));
    $table->add_index('course-time', XMLDB_INDEX_NOTUNIQUE, array('courseid', 'anonymous', 'timecreated'));
    $table->add_index('user-module', XMLDB_INDEX_NOTUNIQUE, array(
        'userid',
        'contextlevel',
        'contextinstanceid',
        'crud',
        'edulevel',
        'timecreated'
    ));

    // Conditionally launch create table.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
}

function create_logstoreid_and_type_columns($dbman) {

    // Select table.
    $table = new xmldb_table('logstore_xapi_log');

    // Conditionally add field logstore_standard_log_id to logstore_xapi_log.
    $field = new xmldb_field('logstore_standard_log_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    // Conditionally add field type to logstore_xapi_log.
    $field = new xmldb_field('type', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    // Select table.
    $table = new xmldb_table('logstore_xapi_failed_log');

    // Conditionally add field logstore_standard_log_id to logstore_xapi_log.
    $field = new xmldb_field('logstore_standard_log_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    // Conditionally add field type to logstore_xapi_log.
    $field = new xmldb_field('type', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }
}

function create_xapi_notification_table($dbman, $tablename) {
    // Define table to be created.
    $table = new xmldb_table($tablename);

    // Adding fields to table.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('failedlogid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('email', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
    $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

    // Adding keys to table.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Adding indexes to table.
    $table->add_index('failedlogid', XMLDB_INDEX_NOTUNIQUE, array('failedlogid'));
    $table->add_index('email', XMLDB_INDEX_NOTUNIQUE, array('email'));
    $table->add_index('timecreated', XMLDB_INDEX_NOTUNIQUE, array('timecreated'));

    // Conditionally launch create table.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
}

function xmldb_logstore_xapi_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2015081001) {
        create_xapi_log_table($dbman, 'logstore_xapi_log');
        upgrade_plugin_savepoint(true, 2015081001, 'logstore', 'xapi');
    }

    if ($oldversion < 2018082100) {
        create_xapi_log_table($dbman, 'logstore_xapi_failed_log');
        upgrade_plugin_savepoint(true, 2018082100, 'logstore', 'xapi');
    }

    if ($oldversion < 2020032700) {

        // Define field errortype to be added to logstore_xapi_failed_log.
        $table = new xmldb_table('logstore_xapi_failed_log');
        $field = new xmldb_field('errortype', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'realuserid');

        // Conditionally launch add field errortype.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field response to be added to logstore_xapi_failed_log.
        $field = new xmldb_field('response', XMLDB_TYPE_TEXT, null, null, null, null, null, 'errortype');

        // Conditionally launch add field response.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xapi savepoint reached.
        upgrade_plugin_savepoint(true, 2020032700, 'logstore', 'xapi');
    }

    if ($oldversion < 2020041506) {
        create_xapi_notification_table($dbman, 'logstore_xapi_notif_sent_log');
        upgrade_plugin_savepoint(true, 2020041506, 'logstore', 'xapi');
    }

    if ($oldversion < 2020050100) {
        create_logstoreid_and_type_columns($dbman);
        upgrade_plugin_savepoint(true, 2020050100, 'logstore', 'xapi');
    }

    return true;
}
