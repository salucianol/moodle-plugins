<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_databasesyncdo
 * @copyright  2020 Samuel Luciano {@link https://koalacreativesoftware.com}
 * @license    None
 */

defined('MOODLE_INTERNAL') || die();

class enrol_databasesyncdo_plugin extends enrol_plugin {
     /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_delete_instance($instance) {
        return false;
    }

    /**
     * Is it possible to hide/show enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_hide_show_instance($instance) {
        return false;
    }

    /**
     * Does this plugin allow manual unenrolment of a specific user?
     * Yes, but only if user suspended...
     *
     * @param stdClass $instance course enrol instance
     * @param stdClass $ue record from user_enrolments table
     *
     * @return bool - true means user with 'enrol/xxx:unenrol' may unenrol this user, false means nobody may touch this user enrolment
     */
    public function allow_unenrol_user(stdClass $instance, stdClass $ue) {
        return false;
    }

    /**
     * Forces synchronisation of user enrolments with external database,
     * does not create new courses.
     *
     * @param progress_trace $trace
     * @return int 0 means success, 1 db connect failure, 2 db read failure, 3 means some missing values from config
     */
    public function synchronize_enrolments(progress_trace $trace) {
        global $CFG, $DB;
        
        require_once($CFG->libdir.'/adodb/adodb.inc.php');

        $time_start = microtime(true);
        $current_date = $this->GetCurrentDate();
        $trace->output("[$current_date] Starting user enrolments synchronization.");

        if(!$this->check_settings($trace)){
            $trace->finished();
            return 3;
        }

        core_php_time_limit::raise();
        raise_memory_limit(MEMORY_HUGE);

        // Testing the connection external database
        $externalDatabaseConnection = $this->test_external_database_connection($this->get_config('external_database_engine'), $this->get_config('external_database_dbhost'), $this->get_config('external_database_dbuser'), $this->get_config('external_database_dbpass'), $this->get_config('external_database_dbname'), $trace);
        if(!$externalDatabaseConnection){
            return 1;
        }

        // Testing the connection to internal database
        $internalDatabaseConnection = $this->test_internal_database_connection($this->get_config('internal_database_engine'), $this->get_config('internal_database_dbhost'), $this->get_config('internal_database_dbuser'), $this->get_config('internal_database_dbpass'), $this->get_config('internal_database_dbname'), $trace);
        if(!$internalDatabaseConnection){
            return 1;
        }

        $enrolments_query = $this->prepare_query_enrolments($this->get_config('enrolments_query'));
        $enrolments = $externalDatabaseConnection->Execute($enrolments_query);

        // Variables for fetching values from recordsets...
        $Usuario = '';
        $RolMoodle = '';
        $NumeroIdCurso = '';
        $FechaRegistro = date_timestamp_get(new DateTime());

        // Query to be executed against Internal Database for inserting enrolments and courses...
        $insert_matriculacion = "INSERT INTO {$this->get_config('internal_database_enrolments_table')}(numero_id, nombre_usuario, rol) VALUES(?,?,?) ON DUPLICATE KEY UPDATE fecha_registro = ?";
        $insertEnrolmentPreparedStatement = $internalDatabaseConnection->Prepare($insert_matriculacion);
        if(!$insertEnrolmentPreparedStatement){
            $current_date = $this->GetCurrentDate();
            $trace->output("<strong>[$current_date] The prepared statement for inserting the enrolments failed.</strong>");
            $trace->output("<strong>[$current_date] ".$internalDatabaseConnection->ErrorMsg().".</strong>");
            $trace->finished();
            return 2;
        }

        $delete_matriculaciones = "DELETE FROM {$this->get_config('internal_database_enrolments_table')} WHERE fecha_registro < ?";
        $deleteEnrolmentPreparedStatement = $internalDatabaseConnection->Prepare($delete_matriculaciones);
        if(!$deleteEnrolmentPreparedStatement){
            $current_date = $this->GetCurrentDate();
            $trace->output("<strong>[$current_date] The prepared statement for deleting the enrolments failed.</strong>");
            $trace->output("<strong>[$current_date] ".$internalDatabaseConnection->ErrorMsg().".</strong>");
            $trace->finished();
            return 2;
        }

        if($enrolments && !$enrolments->EOF){
            // Truncate tables before running the process...
            /*if(!$internalDatabaseConnection->Execute("TRUNCATE TABLE {$this->get_config('internal_database_enrolments_table')}")){
                $current_date = $this->GetCurrentDate();
                $trace->output("<stron
                g>[$current_date] Trying to delete all current records failed.</strong>");
                $trace->output("<strong>[$current_date] ".$internalDatabaseConnection->ErrorMsg().".</strong>");
                $trace->finished();
                return 2;
            }*/

            $internalDatabaseConnection->BeginTrans();
            $count = 0;
            
            while($enrolment = $enrolments->FetchRow()){
                $Usuario = $enrolment[$this->get_config('matching_fields_username')];
                $RolMoodle = $enrolment[$this->get_config('matching_fields_role')];
                $NumeroIdCurso = $enrolment[$this->get_config('matching_fields_course_idnumber')];
    
                if(!$internalDatabaseConnection->Execute($insertEnrolmentPreparedStatement, array($NumeroIdCurso, $Usuario, $RolMoodle, $FechaRegistro))){
                    $current_date = $this->GetCurrentDate();
                    $trace->output("<strong>[$current_date] Failed executing the INSERT statement in the Internal Database for the enrolment.</strong>");
                    $trace->output("<strong>[$current_date] ".print_r($internalDatabaseConnection->ErrorMsg(), true).".</strong>");
                    continue;
                }
                
                if($internalDatabaseConnection->Affected_Rows() < 0){
                    $current_date = $this->GetCurrentDate();
                    $trace->output("<strong>[$current_date] Failed on executing the INSERT for an enrolment on values ('{$Usuario}', '{$NumeroIdCurso}', '{$RolMoodle}', '{$FechaRegistro}').</strong>");
                    $trace->output("<strong>[$current_date] ".$internalDatabaseConnection->ErrorMsg().".</strong>");
                    continue;
                }
                $count++;
            }

            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] {$count} enrolments were inserted.");

            if(!$internalDatabaseConnection->Execute($deleteEnrolmentPreparedStatement, array($FechaRegistro))){
                $current_date = $this->GetCurrentDate();
                $trace->output("<strong>[$current_date] Failed executing the DELETE statement in the Internal Database for the enrolments.</strong>");
                $trace->output("<strong>[$current_date] ".print_r($internalDatabaseConnection->ErrorMsg(), true).".</strong>");
            }

            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] {$internalDatabaseConnection->Affected_Rows()} enrolments were deleted.");

            $internalDatabaseConnection->CommitTrans(true);
        } else {
            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] The executed query for enrolments does not return any records from the database.");
            $trace->output("[$current_date] Used query: ".$enrolments_query);
            $trace->finished();
            return 2;
        }

        $enrolments->Close();
        $externalDatabaseConnection->Close();
        $internalDatabaseConnection->Close();

        \core\task\manager::clear_static_caches();
        
        $time_end = microtime(true);
        $time_elapsed = $time_end - $time_start;
        $current_date = $this->GetCurrentDate();
        $trace->output("[$current_date] Finished user enrolments synchronization.");
        $trace->output("[$current_date] Took {$time_elapsed} seconds to complete.");
        $trace->finished();
        return 0;
    }

    /**
     * Forces synchronisation of all courses with external database.
     *
     * @param progress_trace $trace
     * @return int 0 means success, 1 db connect failure, 2 db read failure, 3 means some missing values from config
     */
    public function synchronize_courses(progress_trace $trace) {
        global $CFG, $DB;

        require_once($CFG->libdir.'/adodb/adodb.inc.php');

        $time_start = microtime(true);
        $current_date = $this->GetCurrentDate();
        $trace->output("[$current_date] Starting courses synchronization.");

        if(!$this->check_settings($trace)){
            $trace->finished();
            return 3;
        }

        core_php_time_limit::raise();
        raise_memory_limit(MEMORY_HUGE);

        // Testing the connection external database
        $externalDatabaseConnection = $this->test_external_database_connection($this->get_config('external_database_engine'), $this->get_config('external_database_dbhost'), $this->get_config('external_database_dbuser'), $this->get_config('external_database_dbpass'), $this->get_config('external_database_dbname'), $trace);
        if(!$externalDatabaseConnection){
            return 1;
        }

        // Testing the connection to internal database
        $internalDatabaseConnection = $this->test_internal_database_connection($this->get_config('internal_database_engine'), $this->get_config('internal_database_dbhost'), $this->get_config('internal_database_dbuser'), $this->get_config('internal_database_dbpass'), $this->get_config('internal_database_dbname'), $trace);
        if(!$internalDatabaseConnection){
            return 1;
        }

        $courses_query = $this->prepare_query_enrolments($this->get_config('courses_query'));
        $courses = $externalDatabaseConnection->Execute($courses_query);

        // Variables for fetching values from recordsets...
        $NumeroIdCurso = '';
        $CategoriaCurso = '';
        $NombreCortoCurso = '';
        $NombreCompletoCurso = '';
        $FechaRegistro = date_timestamp_get(new DateTime());

        $insert_curso = "INSERT INTO {$this->get_config('internal_database_course_table')}(numero_id, nombre_corto, nombre_completo, categoria) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE fecha_registro = ?";
        $insertCoursePreparedStatement = $internalDatabaseConnection->Prepare($insert_curso);
        if(!$insertCoursePreparedStatement){
            $current_date = $this->GetCurrentDate();
            $trace->output("<strong>[$current_date] The prepared statement for inserting the courses failed.</strong>");
            $trace->output("<strong>[$current_date] ".print_r($internalDatabaseConnection->ErrorMsg(), true).".</strong>");
            $trace->finished();
            return 2;
        }

        if($courses && !$courses->EOF){
            // Truncate tables before running the process...
            /*if(!$internalDatabaseConnection->Execute("TRUNCATE TABLE {$this->get_config('internal_database_course_table')}")){
                $current_date = $this->GetCurrentDate();
                $trace->output("<strong>[$current_date] Trying to delete all current records failed.</strong>");
                $trace->output("<strong>[$current_date] ".$internalDatabaseConnection->ErrorMsg().".</strong>");
                $trace->finished();
                return 2;
            }*/
            
            $internalDatabaseConnection->BeginTrans();

            while($course = $courses->FetchRow()){
                $NumeroIdCurso = $course[$this->get_config('matching_fields_course_idnumber')];
                $CategoriaCurso = $course[$this->get_config('matching_fields_course_category')];
                $NombreCortoCurso = $course[$this->get_config('matching_fields_course_shortname')];
                $NombreCompletoCurso = $course[$this->get_config('matching_fields_course_fullname')];
                
                if(!$DB->record_exists('course_categories', ['idnumber' => $CategoriaCurso])){
                    // Category needs to be created...
                    $category = new stdClass();
                    $category->name = $CategoriaCurso;
                    $category->idnumber = $CategoriaCurso;
                    $DB->insert_record('course_categories', $category);
                }

                if(!$internalDatabaseConnection->Execute($insertCoursePreparedStatement, array($NumeroIdCurso, $NombreCortoCurso, $NombreCompletoCurso, $CategoriaCurso, $FechaRegistro))){
                    $current_date = $this->GetCurrentDate();
                    $trace->output("<strong>[$current_date] Failed executing the INSERT statement in the Internal Database for the courses.</strong>");
                    $trace->output("<strong>[$current_date] ".print_r($internalDatabaseConnection->ErrorMsg(), true).".</strong>");
                }
                
                if($internalDatabaseConnection->Affected_Rows() < 0){
                    $current_date = $this->GetCurrentDate();
                    $trace->output("<strong>[$current_date] Failed on executing the INSERT for a course on values ('{$NumeroIdCurso}', '{$NombreCortoCurso}', '{$NombreCompletoCurso}', '{$CategoriaCurso}').</strong>");
                    $trace->output("<strong>[$current_date] ".$internalDatabaseConnection->ErrorMsg().".</strong>");
                }
            }

            $internalDatabaseConnection->CommitTrans(true);
        } else {
            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] The executed query for courses does not return any records from the database.");
            $trace->output("[$current_date] Used query: ".$courses_query);
            $trace->finished();
            return 2;
        }

        $courses->Close();
        $externalDatabaseConnection->Close();
        $internalDatabaseConnection->Close();

        $time_end = microtime(true);
        $time_elapsed = $time_end - $time_start;
        $current_date = $this->GetCurrentDate();
        $trace->output("[$current_date] Finished courses synchronization.");
        $trace->output("[$current_date] Took {$time_elapsed} seconds to complete.");
        $trace->finished();
        return 0;
    }

    /**
     * Get current date in a custom style format.
     * 
     * @return date object with current date and time
     */
    private function GetCurrentDate() {
        date_default_timezone_set('America/Santo_Domingo');
        return date_format(new DateTime(), 'd/M/Y H:i:s');
    }

    /**
     * Check wether the settings has been set or not.
     * 
     * @return bool 0 means settings not set (at least one), 1 means settings are all set
     */
    private function check_settings(progress_trace $trace){
        if(!$this->get_config('external_database_dbhost') || !$this->get_config('external_database_dbname') || !$this->get_config('external_database_dbuser') || !$this->get_config('external_database_dbpass')){
            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] There are some missing values for the External Database.");
            $trace->finished();
            return false;
        }

        if(!$this->get_config('internal_database_dbhost') || !$this->get_config('internal_database_dbuser') || !$this->get_config('internal_database_dbpass') || !$this->get_config('internal_database_dbhost') || !$this->get_config('internal_database_course_table') || !$this->get_config('internal_database_enrolments_table')){
            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] There are some missing values for the Internal Database.");
            $trace->finished();
            return false;
        }

        if(!$this->get_config('enrolments_query')){
            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] The query for retrieving the enrolments from the External Database is missing.");
            $trace->finished();
            return false;
        }

        if(!$this->get_config('matching_fields_username') || !$this->get_config('matching_fields_role') || !$this->get_config('matching_fields_course_idnumber') || !$this->get_config('matching_fields_course_shortname') || !$this->get_config('matching_fields_course_fullname') || !$this->get_config('matching_fields_course_category')){
            $current_date = $this->GetCurrentDate();
            $trace->output("[$current_date] Some fields name for matching the External Database enrolment fields with the Internal Database enrolment fields are missing.");
            $trace->finished();
            return false;
        }
        return true;
    }

    /**
     * Prepares the enrolments query substiting for the values of each parameter.
     * @param string $query_enrolments
     * @return string
     */
    private function prepare_query_enrolments(string $query){
        $academic_year = $this->get_config('academic_year');
        $is_same_academic_period = $this->get_config('academic_use_same_period');
        $undergraduate_period = $this->get_config('academic_period_undergraduate');
        if(!$is_same_academic_period){
            $postgraduate_period = $this->get_config('academic_period_postgraduate');
            $query = preg_replace(array('/\[\[POSTGRADUATE_PERIOD\]\]/'), array($postgraduate_period), $query);
        }
        return preg_replace(array('/\[\[ACADEMIC_YEAR\]\]/', '/\[\[UNDERGRADUATE_PERIOD\]\]/'), array($academic_year, $undergraduate_period), $query);
    }

    /**
     * Test the connection to the external database.
     * 
     * @return if success return the ADOConnection object, if not false
     */
    private function test_external_database_connection($dbengine, $dbhost, $dbuser, $dbpass, $dbname, $trace){
        // Testing the connection external database
        $externalDatabaseConnection = ADONewConnection($dbengine);
        if(!$externalDatabaseConnection->IsConnected()){
            if(!$externalDatabaseConnection->Connect($dbhost, $dbuser, $dbpass, $dbname, false)){
                $current_date = $this->GetCurrentDate();
                $trace->output("[$current_date] Connection Error: Could not connect to the external database due to: ".$externalDatabaseConnection->ErrorMsg());
                $trace->finished();
                return false;
            }
        }
        $externalDatabaseConnection->SetFetchMode(ADODB_FETCH_ASSOC);
        return $externalDatabaseConnection;
    }
    
    /**
     * Test the connection to the internal database
     * 
     * @return if success return the ADOConnection object, if not false
     */
    private function test_internal_database_connection($dbengine, $dbhost, $dbuser, $dbpass, $dbname, $trace){
        $internalDatabaseConnection = ADONewConnection($dbengine);
        if(!$internalDatabaseConnection->IsConnected()){
            if(!$internalDatabaseConnection->Connect($dbhost, $dbuser, $dbpass, $dbname, false)){
                $current_date = $this->GetCurrentDate();
                $trace->output( "[$current_date] Connection Error: Could not connect to the internal database due to: ".$internalDatabaseConnection->ErrorMsg());
                $trace->finished();
                return false;
            }
        }
        $internalDatabaseConnection->SetFetchMode(ADODB_FETCH_ASSOC);
        $internalDatabaseConnection->SetCharset('utf-8');
        return $internalDatabaseConnection;
    }
}