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

require_once(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('bulkmodifycoursesinfo');

$context = \context_system::instance();

// Check to see if user is logged in
require_login(0, false);

$return_url = 
    new moodle_url('/index.php');

// Check to see if it is an admin user
if(!is_siteadmin()){
    redirect($return_url,
                get_string('message_info_not_admin_user', 
                            'tool_bulkmodifycoursesinfo'));
}

// $PAGE->set_context($context);
// $PAGE->set_pagelayout('admin');
$PAGE->set_url(new moodle_url('/admin/tool/bulkmodifycoursesinfo/bulk_modify_courses.php'));
$PAGE->set_title(get_string('heading_text_bulk_modify_courses', 
                                'tool_bulkmodifycoursesinfo'));

$mform = 
    new tool_bulkmodifycoursesinfo_bulk_modify_courses_form('bulk_modify_courses.php');

if($mform->is_cancelled()){
    redirect($return_url);
} else if($form_data = $mform->get_data()){
    $task_id = \tool_bulkmodifycoursesinfo\helpers\base_helper::
                    generate_unique_task_id();
    $file_name = $task_id.'_'.$mform->get_new_filename('courses_modifications_file');
    $file_manager = get_file_storage();

    $courses_modifications_file = array('contextid' => $context->id,
                                            'component' => 'tool_bulkmodifycoursesinfo',
                                            'filearea' => 'courses_modifications',
                                            'itemid' => 0,
                                            'filepath' => '/',
                                            'filename' => $file_name);

    $file_manager->create_file_from_string($courses_modifications_file,
                                            $mform->get_file_content('courses_modifications_file'));

    $bulk_modify_courses_task = 
        new tool_bulkmodifycoursesinfo_bulk_modify_courses_task();

    $bulk_modify_courses_task->
        set_custom_data(array('courses_modifications_file_name'     => $file_name,
                                'task_id'                           => $task_id,
                                'user_id'                           => $USER->id,
                                'user_full_name'                    => implode(' ', array($USER->firstname,
                                                                                            $USER->lastname))
                            ));

    $bulk_modify_courses_task->set_next_run_time($form_data->run_date_time);

    $bulk_modify_courses_task->set_userid($USER->id);

    \core\task\manager::queue_adhoc_task($bulk_modify_courses_task,
                                            true);

    redirect($return_url,
                get_string('message_info_bulk_modify_courses_task', 
                            'tool_bulkmodifycoursesinfo', 
                            array(
                                'file_name' => $file_name,
                                'task_id' => $task_id,
                            )));
} else {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('heading_text_bulk_modify_courses',
                                        'tool_bulkmodifycoursesinfo'));

    $mform->display();

    echo $OUTPUT->footer();
}