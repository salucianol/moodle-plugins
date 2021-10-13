<?php
if($oldversion < 2021101104){
    // Define table course_renamed to be modified.
    $table = new xmldb_table('course_renamed');

    $field_course_id_number = new xmldb_field('course_id_number',
                                                XMLDB_TYPE_CHAR,
                                                '100',
                                                null,
                                                XMLDB_NOTNULL,
                                                null,
                                                null);

    if($dbman->table_exists($table)){
        if($dbman->field_exists($table,
                                $field_course_id_number)){
            $dbman->drop_field($table,
                                $field_course_id_number);
        }

        $dbman->add_field($table,
                            $field_course_id_number);
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021101104,
                                'tool',
                                'bulkmodifycoursesinfo');
}