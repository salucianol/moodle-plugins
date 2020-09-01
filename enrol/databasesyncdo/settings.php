<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_databasesyncdo
 * @copyright  2020 Samuel Luciano {@link http://koalacreativesoftware.com}
 * @license    None
 */

defined('MOODLE_INTERNAL') || die();

if($ADMIN->fulltree){
    $this_year = date('Y');
    $academic_years = array($this_year => $this_year, $this_year + 1 => $this_year + 1);
    $academic_periods_undergraduate = array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4');
    $academic_periods_postgraduate = array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4');
    $database_engines = array('mssqlnative'=>'mssqlnative', 'mysqli'=>'mysqli');

    $settings->add(new admin_setting_heading('enrol_databasesyncdo_settings_description', '', get_string('pluginname_desc', 'enrol_databasesyncdo')));

    // Adding the configuration settings for the required academic information
    $settings->add(new admin_setting_heading('enrol_databasesyncdo_academic_year', new lang_string('academic_year_header_label', 'enrol_databasesyncdo'), new lang_string('academic_year_header_description', 'enrol_databasesyncdo')));
    $settings->add(new admin_setting_configselect('enrol_databasesyncdo/academic_year', get_string('academic_year_label', 'enrol_databasesyncdo'), get_string('academic_year_description', 'enrol_databasesyncdo'), '', $academic_years));
    $settings->add(new admin_setting_configselect('enrol_databasesyncdo/academic_period_undergraduate', get_string('academic_period_undergradute_label', 'enrol_databasesyncdo'), get_string('academic_period_undergradute_description', 'enrol_databasesyncdo'), '', $academic_periods_undergraduate));
    $settings->add(new admin_setting_configcheckbox('enrol_databasesyncdo/academic_use_same_period', get_string('academic_use_same_period_label', 'enrol_databasesyncdo'), get_string('academic_use_same_period_description', 'enrol_databasesyncdo'), 1));
    $settings->add(new admin_setting_configselect('enrol_databasesyncdo/academic_period_postgraduate', get_string('academic_period_postgradute_label', 'enrol_databasesyncdo'), get_string('academic_period_postgradute_description', 'enrol_databasesyncdo'), '', $academic_periods_postgraduate));

    // Adding the configurations settings for the required External Database information
    $settings->add(new admin_setting_heading('enrol_databasesyncdo_external_database', new lang_string('external_database_header_label', 'enrol_databasesyncdo'), new lang_string('external_database_header_description', 'enrol_databasesyncdo')));
    $settings->add(new admin_setting_configselect('enrol_databasesyncdo/external_database_engine', get_string('external_database_engine_label', 'enrol_databasesyncdo'), get_string('external_database_engine_description', 'enrol_databasesyncdo'), 'mssqlnative', $database_engines));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/external_database_dbhost', get_string('external_database_dbhost_label', 'enrol_databasesyncdo'), get_string('external_database_dbhost_description', 'enrol_databasesyncdo'), 'localhost'));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/external_database_dbname', get_string('external_database_dbname_label', 'enrol_databasesyncdo'), get_string('external_database_dbname_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/external_database_dbuser', get_string('external_database_dbuser_label', 'enrol_databasesyncdo'), get_string('external_database_dbuser_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configpasswordunmask('enrol_databasesyncdo/external_database_dbpass', get_string('external_database_dbpass_label', 'enrol_databasesyncdo'), get_string('external_database_dbpass_description', 'enrol_databasesyncdo'), ''));

    // Adding the configurations settings for the required Internal Database information
    $settings->add(new admin_setting_heading('enrol_databasesyncdo_internal_database', new lang_string('internal_database_header_label', 'enrol_databasesyncdo'), new lang_string('internal_database_header_description', 'enrol_databasesyncdo')));
    $settings->add(new admin_setting_configselect('enrol_databasesyncdo/internal_database_engine', get_string('internal_database_engine_label', 'enrol_databasesyncdo'), get_string('internal_database_engine_description', 'enrol_databasesyncdo'), 'mysqli', $database_engines));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/internal_database_dbhost', get_string('internal_database_dbhost_label', 'enrol_databasesyncdo'), get_string('internal_database_dbhost_description', 'enrol_databasesyncdo'), 'localhost'));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/internal_database_dbname', get_string('internal_database_dbname_label', 'enrol_databasesyncdo'), get_string('internal_database_dbname_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/internal_database_dbuser', get_string('internal_database_dbuser_label', 'enrol_databasesyncdo'), get_string('internal_database_dbuser_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configpasswordunmask('enrol_databasesyncdo/internal_database_dbpass', get_string('internal_database_dbpass_label', 'enrol_databasesyncdo'), get_string('internal_database_dbpass_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/internal_database_course_table', get_string('internal_database_course_table_label', 'enrol_databasesyncdo'), get_string('internal_database_course_table_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/internal_database_enrolments_table', get_string('internal_database_enrolments_table_label', 'enrol_databasesyncdo'), get_string('internal_database_enrolments_table_description', 'enrol_databasesyncdo'), ''));

    // Adding the configurations settings for the required query used to get enrolments and courses from External Database
    $settings->add(new admin_setting_heading('enrol_databasesyncdo_queries', new lang_string('queries_header_label', 'enrol_databasesyncdo'), new lang_string('queries_header_description', 'enrol_databasesyncdo')));
    $settings->add(new admin_setting_configtextarea('enrol_databasesyncdo/enrolments_query', get_string('enrolments_query_label', 'enrol_databasesyncdo'), get_string('enrolments_query_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtextarea('enrol_databasesyncdo/courses_query', get_string('courses_query_label', 'enrol_databasesyncdo'), get_string('courses_query_description', 'enrol_databasesyncdo'), ''));

    // Adding the configurations settings for the required fields to insert the enrolemtns into Internal Database
    $settings->add(new admin_setting_heading('enrol_databasesyncdo_matching_fields', new lang_string('matching_fields_header_label', 'enrol_databasesyncdo'), new lang_string('matching_fields_header_description', 'enrol_databasesyncdo')));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/matching_fields_username', get_string('matching_fields_username_label', 'enrol_databasesyncdo'), get_string('matching_fields_username_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/matching_fields_role', get_string('matching_fields_role_label', 'enrol_databasesyncdo'), get_string('matching_fields_role_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/matching_fields_course_idnumber', get_string('matching_fields_course_idnumber_label', 'enrol_databasesyncdo'), get_string('matching_fields_course_idnumber_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/matching_fields_course_shortname', get_string('matching_fields_course_shortname_label', 'enrol_databasesyncdo'), get_string('matching_fields_course_shortname_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/matching_fields_course_fullname', get_string('matching_fields_course_fullname_label', 'enrol_databasesyncdo'), get_string('matching_fields_course_fullname_description', 'enrol_databasesyncdo'), ''));
    $settings->add(new admin_setting_configtext('enrol_databasesyncdo/matching_fields_course_category', get_string('matching_fields_course_category_label', 'enrol_databasesyncdo'), get_string('matching_fields_course_category_description', 'enrol_databasesyncdo'), ''));
}