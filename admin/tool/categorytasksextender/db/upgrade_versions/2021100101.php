<?php
if($oldversion < 2021100101){
    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_category_removed');

    $table->add_field('id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        XMLDB_SEQUENCE,
                        null);

    $table->add_field('category_id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('category_short_name',
                        XMLDB_TYPE_CHAR,
                        '150',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('task_id',
                        XMLDB_TYPE_CHAR,
                        '15',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('course_id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('course_short_name',
                        XMLDB_TYPE_CHAR,
                        '200',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('removed',
                        XMLDB_TYPE_INTEGER,
                        '1',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        '0');

    $table->add_field('user_id',
                        XMLDB_TYPE_INTEGER,
                        '20',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('user_fullname',
                        XMLDB_TYPE_CHAR,
                        '150',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('created_date',
                        XMLDB_TYPE_INTEGER,
                        '20',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_field('removed_date',
                        XMLDB_TYPE_INTEGER,
                        '20',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    $table->add_key('primary',
                    XMLDB_KEY_PRIMARY,
                    ['id']);

    $table->add_index('ix_task_id',
                        XMLDB_INDEX_NOTUNIQUE,
                        ['task_id']);
    $table->add_index('ix_category_id',
                        XMLDB_INDEX_NOTUNIQUE,
                        ['category_id']);

    if(!$dbman->table_exists($table)){
        $dbman->create_table($table);
    }

    $index_ix_task_id = new xmldb_index('ix_task_id',
                                            XMLDB_INDEX_NOTUNIQUE,
                                            ['task_id']);
    $index_ix_category_id = new xmldb_index('ix_category_id',
                                                XMLDB_INDEX_NOTUNIQUE,
                                                ['category_id']);

    $table = new xmldb_table('course_category_backedup');
    if($dbman->table_exists($table)){
        $dbman->drop_index($table,
                            $index_ix_category_id);
        $dbman->add_index($table,
                            $index_ix_task_id);
    }

    $table = new xmldb_table('course_category_restored');
    if($dbman->table_exists($table)){
        $dbman->drop_index($table,
                            $index_ix_category_id);
        $dbman->add_index($table,
                            $index_ix_task_id);
    }

    $table = new xmldb_table('course_category_reset');
    if($dbman->table_exists($table)){
        $dbman->drop_index($table,
                            $index_ix_category_id);
        $dbman->add_index($table,
                            $index_ix_task_id);
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021100101,
                                'tool',
                                'categorytasksextender');
}