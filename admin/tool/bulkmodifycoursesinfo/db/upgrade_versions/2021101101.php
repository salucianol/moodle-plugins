<?php
if($oldversion < 2021101101){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_renamed');

    $field_created_date = new xmldb_field('created_date',
                                            XMLDB_TYPE_INTEGER,
                                            '20',
                                            null,
                                            XMLDB_NOTNULL,
                                            null,
                                            null);

    if($dbman->table_exists($table)){
        if(!$dbman->field_exists($table,
                                    $field_created_date)){
            $dbman->add_field($table,
                                        $field_created_date);
        }
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021101101,
                                'tool',
                                'bulkmodifycoursesinfo');
}