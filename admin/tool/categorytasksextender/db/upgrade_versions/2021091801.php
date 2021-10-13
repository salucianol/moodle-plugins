<?php
if($oldversion < 2021091801){
    // Define table course_category_backedup to be created.
    $table = new xmldb_table('course_category_backedup');
    
    $field_backed_up_date = new xmldb_field('backed_up_date',
                                                XMLDB_TYPE_INTEGER,
                                                '20',
                                                null,
                                                null,
                                                null,
                                                null);

    if($dbman->table_exists($table)){
        $dbman->add_field($table, 
                            $field_backed_up_date);
    }

    upgrade_plugin_savepoint(true,
                                2021091801,
                                'tool',
                                'categorytasksextender');
}