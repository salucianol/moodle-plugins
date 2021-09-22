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

$category = required_param('categoryid', PARAM_INT);
$context = \context_coursecat::instance($category);

// Check for required permissions
require_login(0, null);
require_capability('tool/categorytasksextender:backupcategorycourses', $context);

$mform = new tool_categorytasksextender_backup_form('backup.php', array('categoryid' => $category));
$form_data = $mform->get_data();
print_r($form_data);
$return_url = new moodle_url('/course/management.php', array('categoryid' => $category));

if($mform->is_cancelled()){
    redirect($return_url);
} else if($form_data){
    redirect($return_url);
} else {
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading(get_string('backup_heading'));

    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('backup_heading').print_r($form_data, true));

    $mform->display();

    echo $OUTPUT->footer();
}