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
 * Process the reset options and start the reset process.
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
require_login(0, null);
require_capability('tool/categorytasksextender:resetcoursescategory', 
                    $context);

$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url(new moodle_url('/admin/tool/categorytasksextender/reset.php'));
$PAGE->set_title(get_string('text_reset_heading',
                            'tool_categorytasksextender',
                            $category->name));
$PAGE->set_heading(get_string('text_reset_heading',
                                'tool_categorytasksextender',
                                $category->name));

$mform = 
    new tool_categorytasksextender_reset_form('reset.php', 
                                                    array('categoryid' => $categoryid));
$form_data = $mform->get_data();
$return_url = 
    new moodle_url('/course/management.php', array('categoryid' => $categoryid));

if(!\tool_categorytasksextender\helpers\base_helper::is_there_any_courses_in_category($category)){
    redirect($return_url,
                get_string('message_error_not_courses_found',
                            'tool_categorytasksextender',
                            $category->name));
}

if($mform->is_cancelled()){
    redirect($return_url);
} else if($form_data){
    $reset_task =
        new tool_categorytasksextender_reset_task();

    $reset_task->set_custom_data(array(
        'category_id'               => $category->id,
        'category_name'             => $category->name,
        'apply_recursiveness'       => $form_data->apply_recursiveness,
        'task_id'                   => \tool_categorytasksextender\helpers\reset_helper
                                        ::generate_unique_task_id(),
        'user_id'                   => $USER->id,
        'user_full_name'            => implode(' ', array($USER->firstname,
                                                        $USER->lastname)),
        'reset_start_date'          => $form_data->reset_start_date,
        'reset_end_date'            => $form_data->reset_end_date,
        'reset_events'              => $form_data->reset_events,
        'reset_notes'               => $form_data->reset_notes,
        'reset_comments'            => $form_data->reset_comments,
        'reset_completion'          => $form_data->reset_completion,
        'delete_blog_associations'  => $form_data->delete_blog_associations,
        'reset_groups_members'      => $form_data->reset_groups_members,
        'reset_competency_ratings'  => $form_data->reset_competency_ratings,
        'reset_gradebook_grades'    => $form_data->reset_gradebook_grades,
        'reset_groups_members'      => $form_data->reset_groups_members,
        'reset_assign_submissions'  => $form_data->reset_assign_submissions,
        'reset_forum_all'           => $form_data->reset_forum_all,

    ));

    $reset_task->set_next_run_time($form_data->run_date_time);
    $reset_task->set_userid($USER->id);

    \core\task\manager::queue_adhoc_task($reset_task,
                                            true);

    redirect($return_url,
                get_string('message_info_reset_task_queue',
                            'tool_categorytasksextender',
                            $category->name));
} else {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('text_reset_heading',
                                        'tool_categorytasksextender',
                                        $category->name));

    $mform->display();

    echo $OUTPUT->footer();
}