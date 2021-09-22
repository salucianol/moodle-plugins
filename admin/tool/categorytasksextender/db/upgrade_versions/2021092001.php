
<?php
if($oldversion < 2021092001){
    // Define table course_category_backedup to be created.
    $table = new xmldb_table('course_category_backedup');

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
        $dbman->drop_index($table,
                            $index_ix_course_id_task_id);
        $dbman->change_field_type($table, 
                                    $field_task_id);
        $dbman->add_index($table,
                                    $index_ix_course_id_task_id);
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021092001,
                                'tool',
                                'categorytasksextender');
}