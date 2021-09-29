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

class reset_helper 
    extends \tool_categorytasksextender\helpers\base_helper {

    public static function populate_table($category_id,
                                            $task_id,
                                            $apply_recursiveness,
                                            $user_id = 2,
                                            $user_full_name = ''){
        global $DB;
        
        // Get the category by ID
        $category = self::get_category_by_id($category_id);

        $categories = array($category->id => $category->name);

        // Get all the courses for this category $category
        $course_list_elements = self::get_courses_by_category($category, $apply_recursiveness);

        // Insert each found course into the backedup control table.
        foreach($course_list_elements as $course_list_element){
            if(!in_array($course_list_element->category, $categories)){
                $category 
                    = \core_course_category::get($course_list_element->category);
                $categories[$category->id] 
                    = self::replace_accented_vowels(str_ireplace(' ', '', $category->name));
            }

            $course_reset = new \stdClass();
            $course_reset->task_id = $task_id;
            $course_reset->category_id = $course_list_element->category;
            $course_reset->category_short_name = $categories[$course_list_element->category];
            $course_reset->course_id = $course_list_element->id;
            $course_reset->course_short_name = $course_list_element->shortname;
            $course_reset->reset = 0;
            $course_reset->created_date = time();
            $course_reset->user_id = $user_id;
            $course_reset->user_fullname = $user_full_name;
            $course_reset->course_start_date = $course_list_element->startdate;
            $course_reset->course_end_date = $course_list_element->enddate;

            $DB->insert_record('course_category_reset', 
                                $course_reset, 
                                false);
        }
    }

}