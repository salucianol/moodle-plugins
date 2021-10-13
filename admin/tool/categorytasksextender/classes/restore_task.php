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
require_once(__DIR__.'/../../../../backup/util/includes/backup_includes.php');
require_once(__DIR__.'/../../../../backup/util/includes/restore_includes.php');

class tool_categorytasksextender_restore_task 
    extends \core\task\adhoc_task {

    public function execute(){
        global $DB, $CFG;

        $data = $this->get_custom_data();

        mtrace("Task Restore to Category: {$data->category_name} (Task ID: {$data->task_id})");
        mtrace("Task Restore to Category: {$data->category_name} has started.");

        // Check if control table has been populated for this task
        if($DB->count_records('course_category_restored',
                                array('task_id' => $data->task_id)) < 1){
            \tool_categorytasksextender\helpers\restore_helper::populate_table($data->category_id,
                                                                                $data->restore_files_path,
                                                                                $data->task_id,
                                                                                $data->apply_recursiveness,
                                                                                $data->user_id,
                                                                                $data->user_full_name);
        }

        // Get all the courses to backup from categories
        //      and subcategories if apply_recursiveness is true
        $files = $DB->get_records('course_category_restored',
                                        array('task_id'     => $data->task_id,
                                                'restored'  => 0));
        $files_count = count($files);

        mtrace("Task Restore to Category: Found {$files_count} files to restore in category {$data->category_name}.");

        if($files_count > 0){
            // Count processed courses
            $files_processed_count = 0;

            // Increase memory size to the limit in case there is a large file to restore
            raise_memory_limit(MEMORY_EXTRA);

            // Read previous loaded list of courses
            //      restore the course using the backup file
            //      set the restored field to false (1)
            //      set the restored_date to time() date/time
            //      set the course_restored_id to the id of the restored course
            foreach($files as $file){
                mtrace("Task Restore to Category: Restoring file {$file->restore_file_path} to category {$file->category_short_name}.");

                $transaction = $DB->start_delegated_transaction();

                $course_id = restore_dbops::create_new_course('', '', $file->category_id);
                
                $file_restore_files_folder = make_backup_temp_directory($file->task_id.'-'.$file->id);
                $file_restore_processor = get_file_packer('application/vnd.moodle.backup');
                $file_restore_processor->extract_to_pathname($file->restore_file_path, $file_restore_files_folder);

                $restore_controller = new restore_controller($file->task_id.'-'.$file->id,
                                                                $course_id,
                                                                backup::INTERACTIVE_NO,
                                                                backup::MODE_GENERAL,
                                                                $file->user_id,
                                                                backup::TARGET_NEW_COURSE);

                $restore_controller->execute_precheck();
                $restore_controller->execute_plan();

                $transaction->allow_commit();

                $files_processed_count++;
                $courses_processed_percentage = 
                    round(($files_processed_count / $files_count) * 100, 2);

                mtrace("Task Restore to Category: {$file->restore_file_path} restored in category {$file->category_short_name}.");
                mtrace("Task Restore to Category: {$files_processed_count} files restored out of {$files_count} ({$courses_processed_percentage}%).");
                
                $DB->update_record('course_category_restored',
                                    array('id'                      => $file->id,
                                            'restored_date'         => time(),
                                            'restored'              => 1,
                                            'course_restored_id'    => $restore_controller->get_courseid()));

                $restore_controller->destroy();
            }
        }

        mtrace("Task Restore to Category: {$data->category_name} has finished.");
    }
}