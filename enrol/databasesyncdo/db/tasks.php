<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_database_sync_do
 * @copyright  2020 Samuel Luciano {@link http://koalacreativesoftware.com}
 * @license    None
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => '\enrol_databasesyncdo\task\sync_enrolment_database_do',
        'blocking' => 0,
        'minute' => '0,30',
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
        'disabled' => 0
    )
);