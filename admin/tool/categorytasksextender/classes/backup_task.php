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
require_once(__DIR__.'/../../../../backup/util/includes/restore_includes.php');

class tool_categorytasksextender_backup_task 
    extends \core\task\adhoc_task {

    public function execute(){
        global $DB;

        $data = $this->get_custom_data();

        mtrace("Task Backups from Category: {$data->category_name} (Task ID: {$data->task_id})");
        mtrace("Task Backups from Category: {$data->category_name} has started.");

        // Check if control table has been populated for this task
        if($DB->count_records('course_category_backedup',
                                array('task_id' => $data->task_id)) < 1){
            \tool_categorytasksextender\helpers\backup_helper::populate_table($data->category_id,
                                                                                $data->task_id,
                                                                                $data->apply_recursiveness,
                                                                                $data->user_id,
                                                                                $data->user_full_name);
        }

        // Get all the courses to backup from categories
        //      and subcategories if apply_recursiveness is true
        $courses = $DB->get_records('course_category_backedup',
                                        array('task_id'     => $data->task_id,
                                                'processed' => 0));
        $courses_count = count($courses);

        mtrace("Task Backups from Category: Found {$courses_count} courses in category {$data->category_name}.");

        if($courses_count > 0){
            // Count processed courses
            $courses_processed_count = 0;

            // Read previous loaded list of courses
            //      make the backup using th backup_controller class
            //      set the processed field to false (1)
            //      set the backed_up_date to time() date/time
            //      set the filet_path to the path of the backup file was saved to.
            foreach($courses as $course){
                $backup_controller = new backup_controller(backup::TYPE_1COURSE,
                                                                $course->course_id,
                                                                backup::FORMAT_MOODLE,
                                                                backup::INTERACTIVE_YES,
                                                                backup::MODE_GENERAL,
                                                                2);
                
                $category_path = $data->backup_path
                                    .$course->category_short_name
                                    .'/'
                                    .$data->task_id;

                if($data->category_folder_creation 
                        && !file_exists($category_path)){
                    mkdir($category_path,
                            0777,
                            true);
                }

                $backup_file_name = 'course_'
                                        .$course->course_short_name
                                        .'_id_'
                                        .$course->course_id
                                        .'-catid_'
                                        .$course->category_id
                                        .'-'
                                        .date('Ymd')
                                        .'-'
                                        .date('His')
                                        .'_'
                                        .$data->task_id;

                $backup_controller
                    ->get_plan()
                    ->get_setting('filename')
                    ->set_value($backup_file_name);

                $backup_file_name = $category_path
                                        .'/'
                                        .$backup_file_name
                                        .'.mbz';

                mtrace("Task Backups from Category: Backing up course {$course->course_short_name} from  category {$course->category_short_name}.");

                $backup_controller->finish_ui();
                $backup_controller->execute_plan();

                $backup_results = $backup_controller->get_results();
                $backup_file = $backup_results['backup_destination'];

                $backup_file->copy_content_to($backup_file_name);

                $courses_processed_count++;
                $courses_processed_percentage = 
                    round(($courses_processed_count / $courses_count) * 100, 2);

                mtrace("Task Backups from Category: {$course->course_short_name} backed up in {$backup_file_name}.");
                mtrace("Task Backups from Category: {$courses_processed_count} processed out of {$courses_count} ({$courses_processed_percentage}%).");
                
                $DB->update_record('course_category_backedup',
                                    array('id'                  => $course->id,
                                            'backed_up_date'    => time(),
                                            'processed'         => 1,
                                            'file_path'         => $backup_file_name));

                $backup_file->delete();
                $backup_controller->destroy();
            }
        }

        mtrace("Task Backups from Category: {$data->category_name} has finished.");
    }
}