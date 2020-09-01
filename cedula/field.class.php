<?php
/**
 * @package    profilefield_cedula
 * @category   profilefield
 * @copyright  2020 BCRD
 */

class profile_field_cedula extends profile_field_base {

    /**
     * Constructor
     *
     * Pulls out the options for cedula from the database and sets the
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
        $mform->addRule($this->inputname, get_string('format_error', 'profilefield_cedula'), 'regex', $regex, 'client', false, false);
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
        $cedula = $usernew->{$this->inputname};
        $regex = isset($this->field->param1) ? $this->field->param1 : '/^.*$/';
        $regex = preg_match("/^\/.*\/$/", $regex) ? $regex : "/".$regex."/";
        if(!preg_match($regex, $cedula)) {
            $errors[$this->inputname] = get_string('format_error', 'profilefield_cedula');
            return $errors;
        }
        $isValidCedula = $this->validate_cedula(str_replace("-", "", $cedula));
        switch($isValidCedula){
            case -1:
                $errors[$this->inputname] = get_string('format_error_empty_string', 'profilefield_cedula');
                break;
            case 0:
                $errors[$this->inputname] = get_string('format_error_required_length', 'profilefield_cedula');
                break;
            case 1:
                $errors[$this->inputname] = get_string('format_error_not_valid', 'profilefield_cedula');
                break;
            case 2:
                break;
            default:
                $errors[$this->inputname] = get_string('format_error_unknown', 'profilefield_cedula');
        }
        if($this->check_exists_cedula($cedula)){
            $errors[$this->inputname] = get_string('duplicated_value', 'profilefield_cedula');
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

    /**
     * Validate the cedula value.
     * 
     * @param string cedula the value to validate
     * @return int -1 means empty string, 0 means value is not 11 characters length, 1 means last digit did not match value, 2 means it is OK
     */
    private function validate_cedula($cedula){
        if(!isset($cedula) or empty($cedula)){
            return -1;
        }
        if(strlen($cedula) != 11){
            return 0;
        }
        $validator_number = "1212121212";
        $sum = 0;
        for($i = 0;$i <= 9;$i++){
            $tempNumber = substr($cedula, $i, 1) * substr($validator_number, $i, 1);
            if($tempNumber > 9){
                $tempNumber = ($tempNumber - 10) + 1;
            }
            $sum += $tempNumber;
        }
        $tempNumber = $sum % 10;
        $tempNumber = (10 - $tempNumber) % 10;
        if($tempNumber == substr($cedula, 10, 1)){
            return 2;
        }
        return 1;
    }

    /**
     * Check if a user is registered with the same cedula.
     * 
     * @param string    cedula the value to validate
     * @return bool     true means there is a record with the same cedula, false there is not.
     */
    private function check_exists_cedula($cedula){
        global $DB;

        return $DB->record_exists('user_info_data', array('data' => $cedula));
    }
}


