<?php
if($oldversion < 2021091804){
    // Define table course_category_backedup to be created.
    $table = new xmldb_table('course_category_backedup');

    $field_category_short_name = new xmldb_field('category_short_name',
                                        XMLDB_TYPE_CHAR,
                                        '150',
                                        null,
                                        XMLDB_NOTNULL,
                                        null,
                                        null);

    if($dbman->table_exists($table)){
        $dbman->add_field($table, 
                            $field_category_short_name);
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021091804,
                                'tool',
                                'categorytasksextender');
}