
<?php
if($oldversion < 2021092807){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_category_reset');

    $field_reset_date = new xmldb_field('reset_date',
                                        XMLDB_TYPE_INTEGER,
                                        '20',
                                        null,
                                        null,
                                        null,
                                        null);

    if($dbman->table_exists($table)){
        if($dbman->field_exists($table,
                                    $field_reset_date)){
            $dbman->change_field_notnull($table,
                                        $field_reset_date);
        } else {
            $dbman->add_field($table,
                                $field_reset_date);
        }
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021092807,
                                'tool',
                                'categorytasksextender');
}