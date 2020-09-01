<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_databasesyncdo
 * @copyright  2020 Samuel Luciano {@link http://koalacreativesoftware.com}
 * @license    None
 */

$string['pluginname'] = 'Enrolment Syncronization for External Database';
$string['pluginname_desc'] = 'This plugins is meant to be a quick selector for the academic year and academic period of time for enrolling the users using the external database enrolment plugin. This is a support plugin for adding an scheduled task for syncronization between and external database and an internal database for Moodle enrolments.';
$string['academic_year_header_label'] = 'Academic Year Selection';
$string['academic_year_header_description'] = 'Please choose the required options for the synchronization process from the External Database to the Internal Database.';
$string['academic_year_label'] = 'Academic Year';
$string['academic_year_description'] = 'Choose the academic year for the synchronization. If you need to use this value in your query below, you can use the variable [[ACADEMIC_YEAR]] in it wherever you want this value is used.';
$string['academic_period_undergradute_label'] = 'Undergradute Academic Period';
$string['academic_period_undergradute_description'] = 'Choose the academic period for the undergradute enrolments. If you need to use this value in your query below, you can use the variable [[UNDERGRADUATE_PERIOD]] in it wherever you want this value is used.';
$string['academic_use_same_period_label'] = 'Use same Undergraduate Academic Period as Postgraduate?';
$string['academic_use_same_period_description'] = 'Choose whether the academic period for the undergradute enrolments is the same number as the postgraduate academic period or not for your institution.';
$string['academic_period_postgradute_label'] = 'Postgraduate Academic Period';
$string['academic_period_postgradute_description'] = 'Choose the academic period for the postgradute enrolments. If you need to use this value in your query below, you can use the variable [[POSTGRADUATE_PERIOD]] in it wherever you want this value is used.';

$string['external_database_header_label'] = 'External Database Settings';
$string['external_database_header_description'] = 'Provide the external database information for the synchronization process.';
$string['external_database_engine_label'] = 'External Database Type';
$string['external_database_engine_description'] = 'Choose the database engine for making the connection to the external database.';
$string['external_database_dbhost_label']= 'External Database Hostname';
$string['external_database_dbhost_description'] = 'Provide the hostname where is located the external database.';
$string['external_database_dbname_label'] = 'External Database Name';
$string['external_database_dbname_description'] = 'Provide the name of the external database which is going to be used for this connection.';
$string['external_database_dbuser_label'] = 'External Database Username';
$string['external_database_dbuser_description'] = 'Provide the external database username for connecting to this external database host.';
$string['external_database_dbpass_label'] = 'External Database Password';
$string['external_database_dbpass_description'] = 'Provide the external database password for connecting to this external database host.';

$string['internal_database_header_label'] = 'Internal Database Settings';
$string['internal_database_header_description'] = 'Provide the internal database information for the synchronization process.';
$string['internal_database_engine_label'] = 'Internal Database Type';
$string['internal_database_engine_description'] = 'Choose the database engine for making the connection to the internal database.';
$string['internal_database_dbhost_label']= 'Internal Database Hostname';
$string['internal_database_dbhost_description'] = 'Provide the hostname where is located the internal database.';
$string['internal_database_dbname_label'] = 'Internal Database Name';
$string['internal_database_dbname_description'] = 'Provide the name of the internal database which is going to be used for this connection.';
$string['internal_database_dbuser_label'] = 'Internal Database Username';
$string['internal_database_dbuser_description'] = 'Provide the internal database username for connecting to this internal database host.';
$string['internal_database_dbpass_label'] = 'Internal Database Password';
$string['internal_database_dbpass_description'] = 'Provide the internal database password for connecting to this internal database host.';
$string['internal_database_course_table_label'] = 'Internal Database Courses Table';
$string['internal_database_course_table_description'] = 'Provide the internal database table for inserting the courses.';
$string['internal_database_enrolments_table_label'] = 'Internal Database Enrolments Table';
$string['internal_database_enrolments_table_description'] = 'Provide the internal database table for inserting the enrolments.';

$string['queries_header_label'] = 'External Database Enrolments Query';
$string['queries_header_description'] = 'Configure the external database query which is going to be used for retrieving the external enrolments for users.';
$string['enrolments_query_label'] = 'Enrolments Query';
$string['enrolments_query_description'] = 'Provide the SQL query which is going to be used for retrieving the enrolments from the external database.';
$string['courses_query_label'] = 'Courses Query';
$string['courses_query_description'] = 'Provide the SQL query which is going to be used for retrieving the courses from the external database.';

$string['matching_fields_header_label'] = 'Fields Matching';
$string['matching_fields_header_description'] = 'Provide the name of the External Database fields which are going to match each required field for the enrolment process.';
$string['matching_fields_username_label'] = 'Username Field Name';
$string['matching_fields_username_description'] = 'Provide the name of the matching field for the username field.';
$string['matching_fields_role_label'] = 'Role Field Name';
$string['matching_fields_role_description'] = 'Provide the name of the matching field for the role field.';
$string['matching_fields_course_idnumber_label'] = 'Course IdNumber Field Name';
$string['matching_fields_course_idnumber_description'] = 'Provide the name of the matching field for the course idnumber field.';
$string['matching_fields_course_shortname_label'] = 'Course Shortname Field Name';
$string['matching_fields_course_shortname_description'] = 'Provide the name of the matching field for the course shortname field.';
$string['matching_fields_course_fullname_label'] = 'Course Fullname Field Name';
$string['matching_fields_course_fullname_description'] = 'Provide the name of the matching field for the course fullname field.';
$string['matching_fields_course_category_label'] = 'Course Category Field Name';
$string['matching_fields_course_category_description'] = 'Provide the name of the matching field for the course category field.';

$string['privacy:metadata'] = 'This plugins does not store any personal information from the user.';
$string['sync_enrolments_database_do:scheduled_task:name'] = 'Enrolment Synchronization for External Database Task';