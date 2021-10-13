
<?php
if($oldversion < 2021092801){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_category_reset');

    $field_category_short_name = new xmldb_field('category_short_name',
                                                    XMLDB_TYPE_CHAR,
                                                    '150',
                                                    null,
                                                    XMLDB_NOTNULL,
                                                    null,
                                                    null);
    $field_course_short_name = new xmldb_field('course_short_name',
                                                XMLDB_TYPE_CHAR,
                                                '200',
                                                null,
                                                XMLDB_NOTNULL,
                                                null,
                                                null);
    $field_user_id = new xmldb_field('user_id',
                                        XMLDB_TYPE_INTEGER,
                                        '20',
                                        null,
                                        XMLDB_NOTNULL,
                                        null,
                                        null);
    $field_user_fullname = new xmldb_field('user_fullname',
                                        XMLDB_TYPE_CHAR,
                                        '150',
                                        null,
                                        XMLDB_NOTNULL,
                                        null,
                                        null);
    $field_course_start_date = new xmldb_field('course_start_date',
                                                XMLDB_TYPE_INTEGER,
                                                '20',
                                                null,
                                                XMLDB_NOTNULL,
                                                null,
                                                null);
    
    $field_course_end_date = new xmldb_field('course_end_date',
                                                XMLDB_TYPE_INTEGER,
                                                '20',
                                                null,
                                                XMLDB_NOTNULL,
                                                null,
                                                null);

    $field_task_id = new xmldb_field('task_id',
                                        XMLDB_TYPE_CHAR,
                                        '15',
                                        null,
                                        XMLDB_NOTNULL,
                                        null,
                                        null);
    $index_ix_course_id_task_id = new xmldb_index('ix_course_id_task_id',
                                                    XMLDB_INDEX_UNIQUE,
                                                    array('course_id', 'task_id'));

    if($dbman->table_exists($table)){
        $dbman->add_field($table, 
                            $field_user_id);
        $dbman->add_field($table, 
                            $field_user_fullname);
        $dbman->add_field($table, 
                            $field_category_short_name);
        $dbman->add_field($table, 
                            $field_course_short_name);
        $dbman->add_field($table, 
                            $field_course_start_date);
        $dbman->add_field($table, 
                            $field_course_end_date);
        
        if($dbman->index_exists($table, 
                                    $index_ix_course_id_task_id)){
            $dbman->drop_index($table,
                            $index_ix_course_id_task_id);
        }
        $dbman->change_field_type($table,
                                    $field_task_id);
        $dbman->add_index($table,
                            $index_ix_course_id_task_id);
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021092801,
                                'tool',
                                'categorytasksextender');
}