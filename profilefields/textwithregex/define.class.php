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
 * Contains definition of cutsom user profile field.
 *
 * @package    profilefield_textwithregex
 * @category   profilefield
 * @copyright  2012 Rajesh Taneja
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class profile_define_textwithregex extends profile_define_base {

    /**
     * Prints out the form snippet for the part of creating or
     * editing a profile field specific to the current data type
     *
     * @param moodleform $form reference to moodleform for adding elements.
     */
    function define_form_specific($form) {
        //Add elements, set default value and define type of data
        $form->addElement('text', 'defaultdata', get_string('text_defaultdata', 'profilefield_textwithregex'), 'size="50"');
        $form->setType('defaultdata', PARAM_TEXT);

        $form->addElement('text', 'param1', get_string('text_regularexpression', 'profilefield_textwithregex'), 'size="70"');
        $form->setDefault('param1', '^.*$');
        $form->setType('param1', PARAM_TEXT);

        $form->addElement('text', 'param2', get_string('text_fieldsize', 'profilefield_textwithregex'), 'size="10"');
        $form->setDefault('param2', 30);
        $form->setType('param2', PARAM_INT);

        $form->addElement('text', 'param3', get_string('text_fieldmaxlength', 'profilefield_textwithregex'), 'size="10"');
        $form->setDefault('param3', 2048);
        $form->setType('param3', PARAM_INT);
    }
}