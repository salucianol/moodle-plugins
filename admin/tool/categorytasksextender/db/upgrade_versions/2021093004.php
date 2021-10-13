<?php
if($oldversion < 2021093004){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_category_restored');

    $field_task_id = new xmldb_field('task_id',
                                        XMLDB_TYPE_CHAR,
                                        '15',
                                        null,
                                        XMLDB_NOTNULL,
                                        null,
                                        null);

    if($dbman->table_exists($table)){
        if($dbman->field_exists($table,
                                    $field_task_id)){
            $dbman->change_field_type($table,
                                        $field_task_id);
        }
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021093004,
                                'tool',
                                'categorytasksextender');
}