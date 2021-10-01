<?php
if($oldversion < 2021093002){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_category_restored');

    $field_file_path = new xmldb_field('file_path',
                                        XMLDB_TYPE_CHAR,
                                        '500',
                                        null,
                                        null,
                                        null,
                                        null);

    $field_restore_file_path = new xmldb_field('restore_file_path',
                                                XMLDB_TYPE_CHAR,
                                                '500',
                                                null,
                                                null,
                                                null,
                                                null);

    $field_restored_date = new xmldb_field('restored_date',
                                            XMLDB_TYPE_INTEGER,
                                            '20',
                                            null,
                                            null,
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

    $field_category_short_name = new xmldb_field('category_short_name',
                                                    XMLDB_TYPE_CHAR,
                                                    '150',
                                                    null,
                                                    XMLDB_NOTNULL,
                                                    null,
                                                    null);

    $field_course_restored_id = new xmldb_field('course_restored_id',
                                                    XMLDB_TYPE_INTEGER,
                                                    '10',
                                                    null,
                                                    null,
                                                    null,
                                                    null);

    $field_restored_date = new xmldb_field('restored_date',
                                            XMLDB_TYPE_INTEGER,
                                            '20',
                                            null,
                                            null,
                                            null,
                                            null);

    $index_ix_course_id_task_id = new xmldb_index('ix_course_id_task_id',
                                                    XMLDB_INDEX_UNIQUE,
                                                    array('course_restored_id', 'task_id'));

    if($dbman->table_exists($table)){
        if($dbman->index_exists($table,
                                    $index_ix_course_id_task_id)){
            $dbman->drop_index($table,
                                $index_ix_course_id_task_id);
        }

        if($dbman->field_exists($table,
                                    $field_file_path)){
            $dbman->drop_field($table,
                                $field_file_path);
        }

        if($dbman->field_exists($table,
                                    $field_course_restored_id)){
            $dbman->change_field_type($table,
                                        $field_course_restored_id);
        } else {
            $dbman->add_field($table,
                                $field_course_restored_id);
        }

        if(!$dbman->field_exists($table,
                                    $field_restore_file_path)){
            $dbman->add_field($table,
                                $field_restore_file_path);
        }
        if(!$dbman->field_exists($table,
                                    $field_user_id)){
            $dbman->add_field($table,
                                $field_user_id);
        }
        if(!$dbman->field_exists($table,
                                    $field_user_fullname)){
            $dbman->add_field($table,
                                $field_user_fullname);
        }
        if(!$dbman->field_exists($table,
                                    $field_category_short_name)){
            $dbman->add_field($table,
                                $field_category_short_name);
        }
        if(!$dbman->field_exists($table,
                                    $field_restored_date)){
            $dbman->add_field($table,
                                $field_restored_date);
        }
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021093002,
                                'tool',
                                'categorytasksextender');
}