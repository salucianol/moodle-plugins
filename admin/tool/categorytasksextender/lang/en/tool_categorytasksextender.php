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
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Category Tasks Extender';
$string['link_text_backup'] = 'Backup courses from';
$string['link_text_restore'] = 'Restore courses into';
$string['link_text_reset'] = 'Reset courses from';
$string['link_text_remove'] = 'Remove courses from';
$string['categorytasksextender:backupcategorycourses'] = 'Create courses backups from category';
$string['categorytasksextender:restorecoursescategory'] = 'Restore courses backups into category';
$string['categorytasksextender:resetcoursescategory'] = 'Reset all courses in category';
$string['tool/categorytasksextender:removecoursescategory'] = 'Remove all courses in category';
$string['text_backup_heading'] = 'Backup all courses from: <strong>{$a}</strong>';
$string['text_reset_heading'] = 'Reset all courses from: <strong>{$a}</strong>';
$string['text_restore_heading'] = 'Restore all courses to: <strong>{$a}</strong>';
$string['text_remove_heading'] = 'Remove all courses from: <strong>{$a}</strong>';
$string['button_backup_proceed'] = 'Proceed';
$string['default_value_backup_path'] = 'Please enter the path where to save the backups...';
$string['default_value_restore_files_path'] = 'Please enter the path where to find the files for restoring...';
$string['field_text_backup_path'] = 'Backups Folder Path';
$string['field_text_apply_recursiveness'] = 'Recursive search?';
$string['field_text_category_folder_creation'] = 'Creates folder for each category?';
$string['field_text_run_date_time'] = 'Date/Time for Running';
$string['field_text_reset_start_date'] = 'Start Date/Time for Reset Course';
$string['field_text_reset_end_date'] = 'End Date/Time for Reset Course';
$string['field_text_restore_files_path'] = 'Restore Folder Path';
$string['field_text_backup_path_help'] = 'Specify the path to use for saving the backups files within the server that Moodle is running in.';
$string['field_text_apply_recursiveness_help'] = 'Specify whether or not you want the task to search for subcategories in a recursive way.';
$string['field_text_category_folder_creation_help'] = 'Specify whether or not the task will create a folder for saving the backups for each category.';
$string['field_text_run_date_time_help'] = 'Specify if you want to run this task in another date/time instead of now.';
$string['field_text_reset_start_date_help'] = 'Specify the start date/time for setting to courses that are being reset.';
$string['field_text_reset_end_date_help'] = 'Specify the end date/time for setting to courses that are being reset.';
$string['field_text_restore_files_path_help'] = 'Specify the path to use for restoring the backups files within the server that Moodle is running in.';
$string['message_info_backup_task_queue'] = 'A Backup Adhoc Task has been schedule for this category <strong>{$a}</strong>.';
$string['message_info_reset_task_queue'] = 'A Reset Adhoc Task has been schedule for this category <strong>{$a}</strong>.';
$string['message_error_not_courses_found'] = 'No courses found neither in category <strong>{$a}</strong> nor in its subcategories.';
$string['message_info_restore_task_queue'] = 'A Restore Adhoc Task has been schedule for this category <strong>{$a}</strong>.';
$string['message_info_remove_task_queue'] = 'A Remove Adhoc Task has been schedule for this category <strong>{$a}</strong>.';
$string['error_message_backup_path_not_exist'] = 'The given backup path does not exist: ';
$string['error_message_backup_path_not_writeable'] = 'The given backup path <strong>({$a})</strong> is not writeable.';
$string['error_message_end_date_before_start_date'] = 'The <strong>End Date</strong> can\'t be set to a date before the <strong>Start Date</strong>.';
$string['error_message_restore_files_path_not_exist'] = 'The  given <strong>restore path</strong> does not exist: <strong>{$a}</strong>.';
$string['error_message_restore_files_path_not_readable'] = 'The given restore path <strong>({$a})</strong> is not readable.';
$string['error_message_restore_files_path_is_empty'] = 'The given restore path (<strong>{$a}</strong>) does not contain any file for restoring neither in it nor in its subfolders.';
$string['header_task_settings'] = 'Task Settings';
$string['header_reset_settings'] = 'Reset Settings';