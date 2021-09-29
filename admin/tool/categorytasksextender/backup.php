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
 * Process the backup options and start the backup process.
 * 
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../config.php');

$categoryid = required_param('categoryid', PARAM_INT);
$category = \core_course_category::get($categoryid);
$context = \context_coursecat::instance($categoryid);

// Check for required permissions
require_login(0, false);
require_capability('tool/categorytasksextender:backupcategorycourses',
                    $context);

$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url(new moodle_url('/admin/tool/categorytasksextender/backup.php'));
$PAGE->set_title(get_string('text_backup_heading', 
                            'tool_categorytasksextender',
                            $category->name));
$PAGE->set_heading(get_string('text_backup_heading', 
                                'tool_categorytasksextender',
                                $category->name));

$mform = 
    new tool_categorytasksextender_backup_form('backup.php', 
                                                    array('categoryid' => $categoryid));
$return_url = 
    new moodle_url('/course/management.php', 
                    array('categoryid' => $categoryid));

if(!\tool_categorytasksextender\helpers\base_helper::is_there_any_courses_in_category($category)){
    redirect($return_url,
                get_string('message_error_not_courses_found',
                            'tool_categorytasksextender',
                            $category->name));
}

if($mform->is_cancelled()){
    redirect($return_url);
} else if($form_data = $mform->get_data()){
    $backup_task = 
        new tool_categorytasksextender_backup_task();

    $backup_task->set_custom_data(array(
        'category_id'               => $category->id,
        'category_name'             => $category->name,
        'backup_path'               => \tool_categorytasksextender\helpers\backup_helper
                                            ::add_end_slash_for_path($form_data->backup_path),
        'category_folder_creation'  => $form_data->category_folder_creation,
        'apply_recursiveness'       => $form_data->apply_recursiveness,
        'task_id'                   => \tool_categorytasksextender\helpers\backup_helper
                                            ::generate_unique_task_id(),
        'user_id'               => $USER->id,
        'user_full_name'        => implode(' ', array($USER->firstname,
                                                        $USER->lastname))
    ));

    $backup_task->set_next_run_time($form_data->run_date_time);
    $backup_task->set_userid($USER->id);

    \core\task\manager::queue_adhoc_task($backup_task,
                                            true);

    redirect($return_url,
                get_string('message_info_backup_task_queue', 
                            'tool_categorytasksextender', 
                            $category->name));
} else {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('text_backup_heading',
                                        'tool_categorytasksextender'));

    $mform->display();

    echo $OUTPUT->footer();
}