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
 * Bulk modify courses information form class.
 * 
 * @package   tool_bulkmodifycoursesinfo
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');

class tool_bulkmodifycoursesinfo_bulk_modify_courses_form extends moodleform {
    public function definition(){
        global $CFG;

        $mform = $this->_form;
        
        $max_file_size = $CFG->maxbytes;
        if($max_file_size <= 0){
            $max_file_size = ini_get('post_max_size');
            $upload_max_size = ini_get('upload_max_size');

            if($upload_max_size < $max_file_size){
                $max_file_size = $upload_max_size;
            }
        }

        // Add element for selecting the file for loading the modifications.
        $mform->addElement('filepicker', 
                            'courses_modifications_file', 
                            get_string('field_text_courses_modifications_file',
                                        'tool_bulkmodifycoursesinfo'), 
                            null,
                            array('maxbytes' => $max_file_size, 
                                    'accepted_types' => array('.csv')));
        $mform->setType('courses_modifications_file', 
                            PARAM_FILE);
        $mform->addHelpButton('courses_modifications_file', 
                                'field_text_courses_modifications_file', 
                                'tool_bulkmodifycoursesinfo');

        $courses_modifications_file_url = 
            new moodle_url('courses_modifications_example.csv');
        
        $courses_modifications_file_link = 
            html_writer::link($courses_modifications_file_url, 'courses_modifications_example.csv');

        $mform->addElement('static', 
                                'courses_modifications_file_sample', 
                                get_string('field_text_example_file_download', 
                                            'tool_bulkmodifycoursesinfo',
                                            'courses_modifications_example.csv'),
                                $courses_modifications_file_link);
        $mform->addHelpButton('courses_modifications_file_sample', 
                                'field_text_example_file_download', 
                                'tool_bulkmodifycoursesinfo');

        // Add an element for selecting (optionally) the date and time to run this task
        $mform->addElement('date_time_selector', 
                            'run_date_time', 
                            get_string('field_text_run_date_time', 
                                        'tool_bulkmodifycoursesinfo'),
                            array('optional' => true));
        $mform->setDefault('run_date_time',
                            time());
        $mform->addHelpButton('run_date_time',
                                'field_text_run_date_time',
                                'tool_bulkmodifycoursesinfo');

        $this->add_action_buttons(true,
                                    get_string('button_text_proceed', 
                                                'tool_bulkmodifycoursesinfo'));
    }

    function validation($data, $files){
        global $USER;

        // TODO: This must be parametrized...
        $csv_required_fields = array(
            'course_id_number',
            'course_new_id_number',
            'course_new_short_name',
            'course_new_start_date',
            'course_new_end_date'
        );
        $csv_optional_fields = array(
            'course_id',
            'course_new_category'
        );

        $errors = parent::validation($data, $files);

        $user_context = \context_user::instance($USER->id);
        $file_storage = get_file_storage();

        if(!$files = $file_storage->get_area_files($user_context->id,
                                                    'user',
                                                    'draft',
                                                    $data['courses_modifications_file'],
                                                    'id',
                                                    false)){
            $errors['courses_modifications_file'] = get_string('message_error_courses_modifications_file_not_given',
                                                                'tool_bulkmodifycoursesinfo',
                                                                get_string('field_text_courses_modifications_file',
                                                                            'tool_bulkmodifycoursesinfo'));
            return $errors;
        }

        $course_modifications_file = $files[array_keys($files)[0]];

        if($course_modifications_file->get_mimetype() !== 'text/csv'){
            $errors['courses_modifications_file'] = get_string('message_error_courses_modifications_file_wrong_format',
                                                                'tool_bulkmodifycoursesinfo',
                                                                array(
                                                                    'file_name' => $course_modifications_file->get_filename(),
                                                                    'file_type' => $course_modifications_file->get_mimetype()
                                                                ));
            return $errors;
        }

        $course_modifications_file_handle = $course_modifications_file->get_content_file_handle();
        $csv_current_fields = fgetcsv($course_modifications_file_handle);
        $csv_missing_fields = array_diff($csv_required_fields,
                                            $csv_current_fields,
                                            $csv_optional_fields);

        if(count($csv_missing_fields) > 0){
            $errors['courses_modifications_file'] = get_string('message_error_courses_modifications_file_missing_fields',
                                                                'tool_bulkmodifycoursesinfo',
                                                                array(
                                                                    'fields'    => implode(', ', $csv_missing_fields),
                                                                    'file_name' => $course_modifications_file->get_filename()
                                                                ));
            return $errors;
        }

        $courses_modifications_count = 0;
        $courses_modifications_lines_with_error = array();
        while($course_modifications = fgetcsv($course_modifications_file_handle)){
            $courses_modifications_count++;

            if(count($course_modifications) < count($csv_required_fields)){
                array_push($courses_modifications_lines_with_error, $courses_modifications_count + 1);
            }
        }

        if($courses_modifications_count < 1){
            $errors['courses_modifications_file'] = get_string('message_error_courses_modifications_file_missing_courses_modifications',
                                                                'tool_bulkmodifycoursesinfo',
                                                                array(
                                                                    'file_name' => $course_modifications_file->get_filename()
                                                                ));
            return $errors;
        }

        if(count($courses_modifications_lines_with_error) > 0){
            $errors['courses_modifications_file'] = get_string('message_error_courses_modifications_file_lines_with_errors',
                                                                'tool_bulkmodifycoursesinfo',
                                                                array(
                                                                    'file_name'         => $course_modifications_file->get_filename(),
                                                                    'lines_with_error'  => implode(', ', $courses_modifications_lines_with_error)
                                                                ));
            return $errors;
        }

        return $errors;
    }
}