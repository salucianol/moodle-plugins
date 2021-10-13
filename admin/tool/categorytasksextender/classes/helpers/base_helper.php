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
 * Base helper class for sharing functions between helpers.
 * 
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_categorytasksextender\helpers;

defined('MOODLE_INTERNAL') || die();

abstract class base_helper {
    /**
     * Returns the $text without any accented vowel.
     */
    protected static function replace_accented_vowels($text){
        $accented_vowels = array('á'=>'a',
                                    'é'=>'e',
                                    'í'=>'i',
                                    'ó'=>'o',
                                    'ú'=>'u',
                                    'Á'=>'A',
                                    'É'=>'E',
                                    'Í'=>'I',
                                    'Ó'=>'O',
                                    'Ú'=>'U');

        return strtr($text, $accented_vowels);
    }
    
    /**
     * Get a category by its id.
     */
    protected static function get_category_by_id($category_id){
        // Get the category by ID
        return \core_course_category::get($category_id);
    }

    /**
     * Search for all the courses within a category whether recursive or not.
     */
    protected static function get_courses_by_category($category, $apply_recursiveness){
        // Get all the courses for this category $category
        return $category->get_courses(array('recursive' => $apply_recursiveness));
    }

    /**
     * Returns the $path given with an end slash in case it does not have one.
     */
    public static function add_end_slash_for_path($path){
        return $path[strlen($path) - 1] == '/' ?
                                            $path : 
                                            $path.'/';
    }

    /**
     * Generate a unique task id.
     */
    public static function generate_unique_task_id(){
        return strtolower(
                    substr(
                        md5(date('YmdHis').random_int(PHP_INT_MIN, PHP_INT_MAX)), 
                        1, 
                        10)
                    );
    }

    /**
     * Check whether the given directory $dir is empty or not.
     */
    public static function is_directory_empty($dir){
        $dir_handler = opendir($dir);
        $is_empty = true;
        
        while (false !== ($dir_entry = readdir($dir_handler))) {
            $is_not_a_default_dir = $dir_entry != "." 
                                    && $dir_entry != "..";

            if ($is_not_a_default_dir) {
                $is_empty = false;
            }
        }

        closedir($dir_handler);
        
        return $is_empty;
    }
}