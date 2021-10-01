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
 * Restore confirmation form class.
 * 
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');

class tool_categorytasksextender_restore_form extends moodleform {
    public function definition(){
        $mform = $this->_form;

        $categoryid = $this->_customdata['categoryid'];
        
        // Add a header element for grouping the options of the task
        $mform->addElement('header',
                            'header_task_settings',
                            get_string('header_task_settings', 'tool_categorytasksextender'));
        $mform->setExpanded('header_task_settings');

        // Add element for capturing the path for saving backups.
        $mform->addElement('text', 
                            'restore_files_path', 
                            get_string('field_text_restore_files_path', 
                                        'tool_categorytasksextender'),
                            array('size' => '80'));
        $mform->setType('restore_files_path', 
                            PARAM_PATH);
        $mform->setDefault('restore_files_path', 
                            get_string('default_value_restore_files_path', 
                                        'tool_categorytasksextender'));
        $mform->addHelpButton('restore_files_path', 
                                'field_text_restore_files_path', 
                                'tool_categorytasksextender');

        // Add element for specifying whether to be recursive or none while searching for categories for courses backups
        $mform->addElement('advcheckbox',
                            'apply_recursiveness',
                            get_string('field_text_apply_recursiveness',
                                        'tool_categorytasksextender'),
                            null,
                            null,
                            array(0, 1));
        $mform->setDefault('apply_recursiveness', 
                            1);
        $mform->addHelpButton('apply_recursiveness', 
                                'field_text_apply_recursiveness', 
                                'tool_categorytasksextender');

        // Add an element for selecting (optionally) the date and time to run this task
        $mform->addElement('date_time_selector', 
                            'run_date_time', 
                            get_string('field_text_run_date_time', 
                                        'tool_categorytasksextender'),
                            array('optional' => true));
        $mform->setDefault('run_date_time',
                            time());
        $mform->addHelpButton('run_date_time',
                                'field_text_run_date_time',
                                'tool_categorytasksextender');
        
        // Add hidden element which hold the category id.
        $mform->addElement('hidden',
                            'categoryid',
                            $categoryid);
        $mform->setType('categoryid',
                            PARAM_INT);

        $this->add_action_buttons(true,
                                    get_string('button_backup_proceed', 
                                                'tool_categorytasksextender'));
    }

    function validation($data, $files){
        $errors = parent::validation($data, $files);
        $is_there_any_error = count($errors) > 0;

        $file_exists = file_exists($data['restore_files_path']);
        $is_readable = is_readable($data['restore_files_path']);

        if(!$is_there_any_error && !$file_exists){
            $errors['restore_files_path'] = get_string('error_message_restore_files_path_not_exist',
                                                        'tool_categorytasksextender',
                                                        $data['restore_files_path']);
            $is_there_any_error = true;
        }
        if(!$is_there_any_error && !$is_readable){
            $errors['restore_files_path'] = get_string('error_message_restore_files_path_not_readable',
                                                        'tool_categorytasksextender',
                                                        $data['restore_files_path']);
            $is_there_any_error = true;
        }
        if(!$is_there_any_error && \tool_categorytasksextender\helpers\restore_helper::is_directory_empty($data['restore_files_path'])){
            $errors['restore_files_path'] = get_string('error_message_restore_files_path_is_empty',
                                                        'tool_categorytasksextender',
                                                        $data['restore_files_path']);
            $is_there_any_error = true;
        }

        return $errors;
    }
}