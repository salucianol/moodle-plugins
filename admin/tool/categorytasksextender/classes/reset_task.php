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
require_once(__DIR__.'/../../../../course/lib.php');

class tool_categorytasksextender_reset_task 
    extends \core\task\adhoc_task {

    public function execute(){
        global $DB;

        $data = $this->get_custom_data();

        mtrace("Task Reset from Category: {$data->category_name} (Task ID: {$data->task_id})");
        mtrace("Task Reset from Category: {$data->category_name} has started.");

        // Check if control table has been populated for this task
        if($DB->count_records('course_category_reset',
                                array('task_id' => $data->task_id)) < 1){
            \tool_categorytasksextender\helpers\reset_helper::populate_table($data->category_id,
                                                                                $data->task_id,
                                                                                $data->apply_recursiveness,
                                                                                $data->user_id,
                                                                                $data->user_full_name);
        }

        // Get all the courses to reset from categories
        //      and subcategories if apply_recursiveness is true
        $courses = $DB->get_records('course_category_reset',
                                        array('task_id'     => $data->task_id,
                                                'reset'     => 0));
        $courses_count = count($courses);

        mtrace("Task Reset from Category: Found {$courses_count} courses in category {$data->category_name}.");

        if($courses_count > 0){
            // Count processed courses
            $courses_processed_count = 0;

            // Read previous loaded list of courses
            //      reset the course using the reset_course_userdata functions
            //      set the reset field to false (1)
            //      set the reset_date to time() date/time
            foreach($courses as $course){
                // Pass the current start and end date for this course to the reset process
                $data->reset_start_date_old = $course->course_start_date;
                $data->reset_end_date_old = $course->course_end_date;

                // Set the course id for the reset process
                $data->id = $course->course_id;

                // Get all the assignable roles for this course to reset each of them.
                $roles = get_assignable_roles(context_course::instance($data->id));
                $roles[0] = get_string('noroles', 'role');
                $data->unenrol_users = array_keys($roles);
                unset($roles);

                mtrace("Task Reset from Category: Resetting course {$course->course_short_name} from  category {$course->category_short_name}.");

                $results = reset_course_userdata($data);
                foreach($results as $result){
                    $result_text = "Task Reset from Category: {$result['component']} {$result['item']} ";
                    if($result['error'] === false){
                        $result_text .= get_string('ok');
                    } else {
                        $result_text .= " present error {$result['error']} while resetting.";
                    }
                    mtrace($result_text);
                }

                $courses_processed_count++;
                $courses_processed_percentage 
                    = round(($courses_processed_count / $courses_count) * 100, 2);

                mtrace("Task Reset from Category: {$course->course_short_name} reset.");
                mtrace("Task Reset from Category: {$courses_processed_count} reset out of {$courses_count} ({$courses_processed_percentage}%).");
                
                $DB->update_record('course_category_reset',
                                    array('id'                  => $course->id,
                                            'reset_date'    => time(),
                                            'reset'         => 1));
            }
        }

        mtrace("Task Reset from Category: {$data->category_name} has finished.");
    }
}