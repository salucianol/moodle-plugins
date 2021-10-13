<?php
if($oldversion < 2021100103){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_category_removed');

    $field_removed_date = new xmldb_field('removed_date',
                                            XMLDB_TYPE_INTEGER,
                                            '20',
                                            null,
                                            null,
                                            null,
                                            null);

    if($dbman->table_exists($table)){
        if($dbman->field_exists($table,
                                    $field_removed_date)){
            $dbman->change_field_type($table,
                                        $field_removed_date);
        }
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021100103,
                                'tool',
                                'categorytasksextender');
}