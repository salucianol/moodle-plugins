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
require_once($CFG->libdir.'/moodlelib.php');

class tool_bulkmodifycoursesinfo_bulk_modify_courses_task
    extends \core\task\adhoc_task {

        public function execute(){
            global $DB;
            
            mtrace('Renaming Courses Task: Process Started.');

            $data = $this->get_custom_data();

            // If the task already inserted the records, skip this process
            if($DB->count_records('course_renamed',
                                array('task_id' => $data->task_id)) < 1){
                $this->populate_table($data->courses_modifications_file_name,
                                        $data->task_id,
                                        $data->user_id,
                                        $data->user_full_name);
            }

            // Get the records for courses modifications from DB
            //      For each course modification update the record in DB for this particular course
            //          Use the course_id field if it's present, if not use the course_id_number
            $courses_modifications = $DB->get_records('course_renamed',
                                                        array(
                                                            'task_id' => $data->task_id,
                                                            'renamed' => 0
                                                        ));
            $courses_modifications_count = count($courses_modifications);

            mtrace("Renaming Courses Task: Found {$courses_modifications_count} for renaming.");
            
            $valid_categories = array();

            foreach($courses_modifications as $course_modification){
                $course_id = $course_modification->course_id;

                if($course_id < 1){
                    $search_field = array(
                        'idnumber' => $course_modification->course_id_number
                    );

                    try{
                        $course = $DB->get_record('course', $search_field, 'id', MUST_EXIST);

                        $course_id = $course->id;
                    } catch(\Exception $e){
                        mtrace("Renaming Courses Task: Course with Id Number {$course_modification->course_id_number} does not exist.");
                        continue;
                    }
                } else if($DB->count_records('course',
                                                array('id' => $course_id)) < 1) {
                    mtrace("Renaming Courses Task: Course with Id {$course_id} does not exist.");
                    continue;
                }

                $course = new \stdClass();
                $course->id = $course_id;
                $course->shortname = $course_modification->new_course_short_name;
                $course->idnumber = $course_modification->new_course_id_number;
                $course->startdate = $course_modification->new_course_start_date;
                $course->enddate = $course_modification->new_course_end_date;

                $is_course_category_valid = $course_modification->new_course_category > 0
                                                &&  (in_array($course_modification->new_course_category, $valid_categories)
                                                        || $DB->record_exists('course_categories',
                                                                                array('id' => $course_modification->new_course_category)));

                if($is_course_category_valid){
                    $course->category = $course_modification->new_course_category;
                    array_push($valid_categories, $course_modification->new_course_category);
                }

                mtrace("Renaming Courses Task: Updating course id {$course_id} with Id Number {$course_modification->course_id_number} to {$course_modification->new_course_id_number}.");

                $DB->update_record('course',
                                    $course,
                                    false);

                $DB->update_record('course_renamed',
                                    array(
                                        'id'        => $course_modification->id,
                                        'renamed'   => 1
                                    ),
                                    false);
            }

            mtrace('Renaming Courses Task: Purging all caches to force regenerating.');

            purge_all_caches();

            mtrace('Renaming Courses Task: Process Finished.');
        }

        private function populate_table($file_name, $task_id, $user_id, $user_full_name){
            global $DB;

            // Get the archive from the file storage
            //      Needs to specify the file area
            //      This in case there is not records on the db for this task.
            $context = \context_system::instance();
            $file_storage = get_file_storage();

            $courses_modifications_file = $file_storage->get_file($context->id,
                                                                    'tool_bulkmodifycoursesinfo',
                                                                    'courses_modifications',
                                                                    0,
                                                                    '/',
                                                                    $file_name);
            if($courses_modifications_file){
                $courses_modifications_file_handle = $courses_modifications_file->get_content_file_handle();
                fgetcsv($courses_modifications_file_handle);

                $courses_modifications_count = 0;

                // Iterate over the file lines to insert into DB the courses modifications
                while($course_modification_line = fgetcsv($courses_modifications_file_handle)){
                    try{
                        $course_modification = 
                            $this->map_course_modification($course_modification_line,
                                                            $task_id,
                                                            $user_id,
                                                            $user_full_name);

                        $DB->insert_record('course_renamed',
                                            $course_modification,
                                            false);

                        $courses_modifications_count++;
                    } catch(\tool_bulkmodifycoursesinfo\exceptions\course_modification_format_exception $e){
                        mtrace("Renaming Courses Task: One of the date was in a wrong format. Please verify the values entered: Start Date {$course_modification_line[3]} or End Date {$course_modification_line[4]}.");
                    } catch(\Exception $e){
                        mtrace("Renaming Courses Task: The Course Id {$course_modification_line[5]} exists in the control table for renamed courses.");
                    }
                }
            }
        }

        private function map_course_modification($course_modification_line, $task_id, $user_id, $user_full_name) {
            $course_modification = new \stdClass();

            if(count($course_modification_line) >= 6){
                $course_modification->new_course_category = intval($course_modification_line[5]);
            }

            if(count($course_modification_line) >= 7){
                $course_modification->course_id = intval($course_modification_line[6]);
            }

            $course_modification->course_id_number = $course_modification_line[0];
            $course_modification->new_course_id_number = $course_modification_line[1];
            $course_modification->new_course_short_name = $course_modification_line[2];

            if(!empty($course_modification_line[3])){
                $temp_date = strtotime($course_modification_line[3]);
                if($temp_date === false){
                    throw new \tool_bulkmodifycoursesinfo\exceptions\course_modification_format_exception();
                }

                $course_modification->new_course_start_date = $temp_date;
            }

            if(!empty($course_modification_line[4])){
                $temp_date = strtotime($course_modification_line[4]);
                if($temp_date === false){
                    throw new \Exception();
                }

                $course_modification->new_course_end_date = $temp_date;
            }

            $course_modification->task_id = $task_id;
            $course_modification->user_id = $user_id;
            $course_modification->user_fullname = $user_full_name;
            $course_modification->created_date = time();
            $course_modification->renamed = 0;

            return $course_modification;
        }

}