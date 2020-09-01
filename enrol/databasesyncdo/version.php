<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_databasesyncdo
 * @copyright  2020 Samuel Luciano {@link https://koalacreativesoftware.com}
 * @license    None
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2020082401;
$plugin->requires = 2019111803;
$plugin->component = 'enrol_databasesyncdo';
$plugin->maturity = MATURITY_STABLE;
$plugin->release= '1.2.1';
$plugin->dependencies = array(
    'enrol_database' => ANY_VERSION,
);