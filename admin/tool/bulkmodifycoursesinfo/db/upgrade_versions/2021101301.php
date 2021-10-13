<?php
if($oldversion < 2021101301){
    // Define table course_renamed to be modified.
    $table = new xmldb_table('course_renamed');

    $field_new_course_category = new xmldb_field('new_course_category',
                                                    XMLDB_TYPE_INTEGER,
                                                    '10',
                                                    null,
                                                    null,
                                                    null,
                                                    null);

    if($dbman->table_exists($table)){
        if(!$dbman->field_exists($table,
                                    $field_new_course_category)){
            $dbman->add_field($table,
                                $field_new_course_category);
        }
    }

    // BulkCourseInfoModifications savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021101301,
                                'tool',
                                'bulkmodifycoursesinfo');
}