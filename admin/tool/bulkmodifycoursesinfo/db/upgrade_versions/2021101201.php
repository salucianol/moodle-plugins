<?php
if($oldversion < 2021101201){
    // Define table course_renamed to be modified.
    $table = new xmldb_table('course_renamed');

    $index_course_renamed_ix_course_id = new xmldb_index('course_renamed_ix_course_id',
                                                            XMLDB_INDEX_UNIQUE,
                                                            array('course_id'));

    $index_course_renamed_ux_course_id = new xmldb_index('course_renamed_ux_course_id',
                                                            XMLDB_INDEX_UNIQUE,
                                                            array('course_id', 'task_id'));

    if($dbman->table_exists($table)){
        if($dbman->index_exists($table,
                                $index_course_renamed_ix_course_id)){
            $dbman->drop_index($table,
                                $index_course_renamed_ix_course_id);
        }

        $dbman->add_index($table,
                            $index_course_renamed_ux_course_id);
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021101201,
                                'tool',
                                'bulkmodifycoursesinfo');
}