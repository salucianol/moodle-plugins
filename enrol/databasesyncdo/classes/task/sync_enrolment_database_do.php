<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_databasesyncdo
 * @copyright  2020 Samuel Luciano {@link http://koalacreativesoftware.com}
 * @license    None
 */

namespace enrol_databasesyncdo\task;
defined('MOODLE_INTERNAL') || die();

 class sync_enrolment_database_do extends \core\task\scheduled_task {
     /**
     * Name for this task.
     *
     * @return string
     */
    public function get_name() {
        return get_string('sync_enrolments_database_do:scheduled_task:name', 'enrol_databasesyncdo');
    }

    /**
     * Run task for synchronising enrolments and courses database.
     */
    public function execute() {

        $trace = new \text_progress_trace();

        if (!enrol_is_enabled('databasesyncdo')) {
            $trace->output('Plugin not enabled');
            return;
        }

        $enrol = enrol_get_plugin('databasesyncdo');

        $enrol->synchronize_courses($trace);
        $enrol->synchronize_enrolments($trace);
    }
 }