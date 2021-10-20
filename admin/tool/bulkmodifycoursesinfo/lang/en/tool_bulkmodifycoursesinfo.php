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
 * @package   tool_bulkmodifycoursesinfo
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Bulk Courses Info Modification';
$string['heading_text_bulk_modify_courses'] = 'Change the information for the courses in the file';
$string['message_info_bulk_modify_courses_task'] = 'A task for changing the information foe the courses within the file {$a->file_name} has been set. <br />Task Id: <strong>{$a->task_id}</strong>';
$string['message_info_not_admin_user'] = 'You are no authorized to see this page because you are not an administrator user.';
$string['field_text_courses_modifications_file'] = 'Courses Modifications File';
$string['field_text_run_date_time'] = 'Date/Time for Running';
$string['field_text_example_file_download'] = 'Download a sample file';
$string['button_text_proceed'] = 'Proceed';
$string['field_text_courses_modifications_file_help'] = 'Select the file in Comma-Separated Format (.csv) which contains the courses modifications information.';
$string['field_text_run_date_time_help'] = 'Specify if you want to run this task in another date/time instead of now.';
$string['field_text_example_file_download_help'] = 'This file can be used as an example of the fields you should send with the information of the courses for modification. <br /><br />This file contains two (2) examples that must be removed.';
$string['message_error_courses_modifications_file_not_given'] = 'The field <strong>{$a}</strong> is required.';
$string['message_error_courses_modifications_file_wrong_format'] = 'The file <strong>{$a->file_name}</strong> has an incorrect file type (<strong>{$a->file_type}</strong>).';
$string['message_error_courses_modifications_file_missing_fields'] = 'There are some missing fields <strong>({$a->fields})</strong> in the file <strong>{$a->file_name}</strong>.';
$string['message_error_courses_modifications_file_missing_courses_modifications'] = 'The file <strong>({$a->file_name})</strong> does not contains any course modification.';
$string['message_error_courses_modifications_file_lines_with_errors'] = 'The file <strong>({$a->file_name})</strong> has courses modifications lines incomplete. <strong>(Lines: {$a->lines_with_error})</strong>';