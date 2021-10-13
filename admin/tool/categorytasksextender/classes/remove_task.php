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
 * Task class for executing the backup process.
 * 
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/../../../../config.php');

class tool_categorytasksextender_remove_task 
    extends \core\task\adhoc_task {

    public function execute(){
        global $DB, $CFG;

        $data = $this->get_custom_data();

        $exists_recyclebin_in_forced_plugins = 
            array_key_exists('tool_recyclebin', $CFG->forced_plugin_settings);

        if (!$exists_recyclebin_in_forced_plugins) {
            $CFG->forced_plugin_settings['tool_recyclebin'] = 
                array('categorybinenable' => false);
        }

        mtrace("Task Remove from Category: {$data->category_name} (Task ID: {$data->task_id})");
        mtrace("Task Remove from Category: {$data->category_name} has started.");

        // Check if control table has been populated for this task
        if($DB->count_records('course_category_removed',
                                array('task_id' => $data->task_id)) < 1){
            \tool_categorytasksextender\helpers\remove_helper::populate_table($data->category_id,
                                                                                $data->task_id,
                                                                                $data->apply_recursiveness,
                                                                                $data->user_id,
                                                                                $data->user_full_name);
        }

        // Get all the courses to backup from categories
        //      and subcategories if apply_recursiveness is true
        $courses = $DB->get_records('course_category_removed',
                                        array('task_id'     => $data->task_id,
                                                'removed' => 0));
        $courses_count = count($courses);

        mtrace("Task Remove from Category: Found {$courses_count} courses in category {$data->category_name}.");

        if($courses_count > 0){
            // Count processed courses
            $courses_processed_count = 0;

            $lock_factory = 
                \core\lock\lock_config::get_lock_factory('tool_categorytasksextender');

            // Read previous loaded list of courses
            //      remove the course from the category
            //      set the removed field to false (1)
            //      set the removed_date to time() date/time
            foreach($courses as $course){
                mtrace("Task Remove from Category: Removing course {$course->course_short_name} from category {$course->category_short_name}.");

                /**
                 * Many thanks to Lafayette College ITS for this code.
                 * 
                 * The plugin tool_deletecourses inspired me to create these additionals tools
                 *      from the delete category action from this plugins.
                 * 
                 * The code below was copy from that plugin just as a way of reusing.
                 * 
                 * This plugin can be found in the Moodle plugins directory in the following link:
                 * https://moodle.org/plugins/tool_deletecourses.
                 */
                $lock_key = "course:{$course->course_id}";
                $course_removed = true;

                if ($lock = $lock_factory->get_lock($lock_key, 0, 600)) {
                    $course_record = 
                        $DB->get_record('course', array('id' => $course->course_id));
                    if ($course_record) {
                        try{
                            if (!delete_course($course_record, true)) {
                                mtrace("Task Remove from Category: Failed to remove course {$course->course_short_name} from category {$course->category_short_name}");
                                $course_removed = false;
                            }
                        } catch(\Exception $e){
                            mtrace("Task Remove from Category: Exception caught in file {$e->getFile()} at {$e->getLine()} with message {$e->getMessage()}"
                                    .PHP_EOL
                                    ."Stacktrace: {$e->getTraceAsString()}");
                            $course_removed = false;
                        }
                    }

                    $lock->release();
                }

                if($course_removed){
                    $courses_processed_count++;
                    $courses_processed_percentage = 
                        round(($courses_processed_count / $courses_count) * 100, 2);

                    mtrace("Task Remove from Category: {$course->course_short_name} remove from {$course->course_short_name}.");
                    mtrace("Task Remove from Category: {$courses_processed_count} processed out of {$courses_count} ({$courses_processed_percentage}%).");
                    
                    $DB->update_record('course_category_removed',
                                        array('id'                  => $course->id,
                                                'removed_date'    => time(),
                                                'removed'         => 1));
                }
            }
        }

        fix_course_sortorder();

        if($courses_processed_count < $courses_count){
            mtrace("Task Remove from Category: {$courses_processed_count} failed to be removed.");
        }

        mtrace("Task Remove from Category: {$data->category_name} has finished.");
    }
}