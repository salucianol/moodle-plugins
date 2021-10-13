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
 * Helper class for the reset task.
 * 
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_categorytasksextender\helpers;

defined('MOODLE_INTERNAL') || die();

class restore_helper 
    extends \tool_categorytasksextender\helpers\base_helper {

    private const DIR_MAX_LEVEL_RECURSION = 4;

    public static function populate_table($category_id,
                                            $restore_files_path,
                                            $task_id,
                                            $apply_recursiveness,
                                            $user_id = 2,
                                            $user_full_name = '',
                                            $dir_level_recursion = 0){
            // Get the category by ID
        $category = self::get_category_by_id($category_id);

        self::populate_table_recursively($category,
                                            $restore_files_path,
                                            $task_id,
                                            $apply_recursiveness,
                                            $user_id,
                                            $user_full_name,
                                            $dir_level_recursion);
    }

    private static function populate_table_recursively($category,
                                                        $restore_files_path,
                                                        $task_id,
                                                        $apply_recursiveness,
                                                        $user_id = 2,
                                                        $user_full_name = '',
                                                        $dir_level_recursion = 0){
        global $DB;

        $restore_files_path = self::add_end_slash_for_path($restore_files_path);
        $dummy_files = array('.', '..');
        $files = scandir($restore_files_path);
        $files = array_diff($files, $dummy_files);

        // Insert each found course into the restored control table.
        foreach($files as $file){
            $file_path = $restore_files_path.$file;

            if(is_dir($file_path) 
                    && $dir_level_recursion < self::DIR_MAX_LEVEL_RECURSION
                    && $apply_recursiveness){
                self::populate_table_recursively($category_id,
                                                    $file_path,
                                                    $task_id,
                                                    $apply_recursiveness,
                                                    $user_id,
                                                    $user_full_name,
                                                    $dir_level_recursion + 1);
            }

            $course_restore = new \stdClass();
            $course_restore->task_id = $task_id;
            $course_restore->category_id = $category->id;
            $course_restore->category_short_name = $category->name;
            $course_restore->restore_file_path = $file_path;
            $course_restore->created_date = time();
            $course_restore->user_id = $user_id;
            $course_restore->user_fullname = $user_full_name;

            $DB->insert_record('course_category_restored', 
                                $course_restore, 
                                false);
        }
    }
}