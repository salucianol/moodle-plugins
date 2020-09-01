<?php
/**
 * Contains definition of custom user profile field.
 *
 * @package    profilefield_cedula
 * @category   profilefield
 * @copyright  2020 BCRD
 */

class profile_define_cedula extends profile_define_base {

    /**
     * Prints out the form snippet for the part of creating or
     * editing a profile field specific to the current data type
     *
     * @param moodleform $form reference to moodleform for adding elements.
     */
    function define_form_specific($form) {
        //Add elements, set default value and define type of data
        $form->addElement('text', 'defaultdata', get_string('text_defaultdata', 'profilefield_cedula'), 'size="50"');
        $form->setType('defaultdata', PARAM_TEXT);

        $form->addElement('text', 'param1', get_string('text_regularexpression', 'profilefield_cedula'), 'size="70"');
        $form->setDefault('param1', '^.*$');
        $form->setType('param1', PARAM_TEXT);

        $form->addElement('text', 'param2', get_string('text_fieldsize', 'profilefield_cedula'), 'size="10"');
        $form->setDefault('param2', 30);
        $form->setType('param2', PARAM_INT);

        $form->addElement('text', 'param3', get_string('text_fieldmaxlength', 'profilefield_cedula'), 'size="10"');
        $form->setDefault('param3', 2048);
        $form->setType('param3', PARAM_INT);
    }
}