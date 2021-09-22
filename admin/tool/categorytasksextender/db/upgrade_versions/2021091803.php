<?php
if($oldversion < 2021091803){
    // Define table course_category_backedup to be created.
    $table = new xmldb_table('course_category_backedup');
    
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

    if($dbman->table_exists($table)){
        $dbman->add_field($table, 
                            $field_user_id);
        $dbman->add_field($table, 
                            $field_user_fullname);
    }

    upgrade_plugin_savepoint(true,
                                2021091803,
                                'tool',
                                'categorytasksextender');
}