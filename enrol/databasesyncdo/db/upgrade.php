<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_database_sync_do
 * @copyright  2020 Samuel Luciano {@link http://koalacreativesoftware.com}
 * @license    None
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_enrol_databasesyncdo_upgrade($oldversion) {
    global $CFG;
    return true;
}