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
 * @package   tool_bulkmodifycoursesinfo
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_bulkmodifycoursesinfo\helpers;

defined('MOODLE_INTERNAL') || die();

abstract class base_helper {
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
}