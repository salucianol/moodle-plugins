<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_database_sync_do
 * @copyright  2020 Samuel Luciano {@link http://koalacreativesoftware.com}
 * @license    None
 */

namespace enrol_database_sync_do\privacy;
defined('MOODLE_INTERNAL') || die();

class provider implements \core_privacy\local\metadata\null_provider {
    /**
     * Get the language string identifier with the component's language
     * file to explain why this plugin stores no data.
     *
     * @return  string
     */
    public static function get_reason() : string {
        return 'privacy:metadata';
    }
}