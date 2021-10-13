<?php
if($oldversion < 2021101102){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_renamed');

    $field_renamed = new xmldb_field('renamed',
                                            XMLDB_TYPE_INTEGER,
                                            '1',
                                            null,
                                            XMLDB_NOTNULL,
                                            0,
                                            null);

    if($dbman->table_exists($table)){
        if(!$dbman->field_exists($table,
                                    $field_renamed)){
            $dbman->add_field($table,
                                        $field_renamed);
        }
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021101102,
                                'tool',
                                'bulkmodifycoursesinfo');
}