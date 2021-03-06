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
 * @package    profilefield_textwithregex
 * @category   profilefield
 * @copyright  2012 Rajesh Taneja
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class profile_field_textwithregex extends profile_field_base {

    /**
     * Constructor
     *
     * Pulls out the options for textwithregex from the database and sets the
     * the corresponding key for the data if it exists
     *
     * @param int $fieldid id of user profile field
     * @param int $userid id of user
     */
    function __construct($fieldid=0, $userid=0, $fielddata = null) {
        global $DB;
        //first call parent constructor
        parent::__construct($fieldid, $userid, $fielddata);

        if (!empty($this->field)) {
            $datafield = $DB->get_field('user_info_data', 'data', array('userid' => $this->userid, 'fieldid' => $this->fieldid));
            if ($datafield !== false) {
                $this->data = $datafield;
            } else {
                $this->data = $this->field->defaultdata;
            }
        }
    }

    /**
     * Adds the profile field to the moodle form class
     *
     * @param moodleform $mform instance of the moodleform class
     */
    function edit_field_add($mform) {
        // Create the form field.
        $regex = isset($this->field->param1) ? $this->field->param1 : '/^.*$/';
        $regex = preg_match("/^\/.*\/$/", $regex) ? $regex : "/".$regex."/";
        $mform->addElement('text', $this->inputname, format_string($this->field->name), '');
        $mform->addRule($this->inputname, get_string('format_error', 'profilefield_textwithregex'), 'regex', $regex, 'client', false, false);
        $mform->setType($this->inputname, PARAM_TEXT);
    }

    /**
     * Display the data for this field
     *
     * @return string data for custom profile field.
     */
    function display_data() {
        return parent::display_data();
    }

    /**
     * Sets the default data for the field in the form object
     *
     * @param moodleform $mform instance of the moodleform class
     */
    function edit_field_set_default($mform) {
        if (!empty($default)) {
            $mform->setDefault($this->inputname, $this->field->defaultdata);
        }
    }

    /**
     * Validate the form field from profile page
     *
     * @param stdClass $usernew user input
     * @return string contains error message otherwise NULL
     **/
    function edit_validate_field($usernew) {
        $errors = array();
        $regex = isset($this->field->param1) ? $this->field->param1 : '/^.*$/';
        $regex = preg_match("/^\/.*\/$/", $regex) ? $regex : "/".$regex."/";
        if(!preg_match($regex, $usernew->{$this->inputname})) {
            $errors[$this->inputname] = get_string('format_error', 'profilefield_textwithregex');
            return $errors;
        }
        return $errors;
    }

    /**
     * Process the data before it gets saved in database
     *
     * @param stdClass $data from the add/edit profile field form
     * @param stdClass $datarecord The object that will be used to save the record
     * @return stdClass
     */
    function edit_save_data_preprocess($data, $datarecord) {
        return $data;
    }

    /**
     * HardFreeze the field if locked.
     *
     * @param moodleform $mform instance of the moodleform class
     */
    function edit_field_set_locked($mform) {
        if (!$mform->elementExists($this->inputname)) {
            return;
        }
        if ($this->is_locked() and !has_capability('moodle/user:update', get_context_instance(CONTEXT_SYSTEM))) {
            $mform->hardFreeze($this->inputname);
            $mform->setConstant($this->inputname, $this->data);
        }
    }
}


