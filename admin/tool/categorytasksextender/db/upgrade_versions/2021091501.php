<?php
if ($oldversion < 2021091501) {
    // Define table course_category_backedup to be created.
    $table = new xmldb_table('course_category_backedup');

    // Adding fields to table course_category_backedup.
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
    $table->add_field('course_id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);
    $table->add_field('task_id',
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
    $table->add_field('file_path',
                        XMLDB_TYPE_CHAR,
                        '500',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);
    $table->add_field('processed',
                        XMLDB_TYPE_INTEGER,
                        '1',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        '0');
    $table->add_field('created_date',
                        XMLDB_TYPE_INTEGER,
                        '20',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    // Adding keys to table course_category_backedup.
    $table->add_key('primary',
                        XMLDB_KEY_PRIMARY,
                        ['id']);

    // Adding indexes to table course_category_backedup.
    $table->add_index('ix_course_id_task_id',
                        XMLDB_INDEX_UNIQUE,
                        ['course_id', 'task_id']);
    $table->add_index('ix_category_id',
                        XMLDB_INDEX_NOTUNIQUE,
                        ['category_id']);

    // Conditionally launch create table for course_category_backedup.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Define table course_category_restored to be created.
    $table = new xmldb_table('course_category_restored');

    // Adding fields to table course_category_restored.
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
    $table->add_field('task_id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);
    $table->add_field('course_restored_id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);
    $table->add_field('file_path',
                        XMLDB_TYPE_CHAR,
                        '500',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);
    $table->add_field('restored',
                        XMLDB_TYPE_INTEGER,
                        '1',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        '0');
    $table->add_field('created_date',
                        XMLDB_TYPE_INTEGER,
                        '20',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    // Adding keys to table course_category_restored.
    $table->add_key('primary',
                        XMLDB_KEY_PRIMARY,
                        ['id']);

    // Adding indexes to table course_category_restored.
    $table->add_index('ix_course_id_task_id',
                        XMLDB_INDEX_UNIQUE,
                        ['course_restored_id', 'task_id']);
    $table->add_index('ix_category_id',
                        XMLDB_INDEX_NOTUNIQUE,
                        ['category_id']);

    // Conditionally launch create table for course_category_restored.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Define table course_category_reset to be created.
    $table = new xmldb_table('course_category_reset');

    // Adding fields to table course_category_reset.
    $table->add_field('id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        XMLDB_SEQUENCE,
                        null);
    $table->add_field('task_id',
                        XMLDB_TYPE_INTEGER,
                        '10',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);
    $table->add_field('category_id',
                        XMLDB_TYPE_INTEGER,
                        '10',
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
    $table->add_field('reset',
                        XMLDB_TYPE_INTEGER,
                        '1',
                        null,   
                        XMLDB_NOTNULL,
                        null,
                        '0');
    $table->add_field('created_date',
                        XMLDB_TYPE_INTEGER,
                        '20',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);
    $table->add_field('reset_date',
                        XMLDB_TYPE_INTEGER,
                        '20',
                        null,
                        XMLDB_NOTNULL,
                        null,
                        null);

    // Adding keys to table course_category_reset.
    $table->add_key('primary',
                        XMLDB_KEY_PRIMARY,
                        ['id']);

    // Adding indexes to table course_category_reset.
    $table->add_index('ix_course_id_task_id',
                        XMLDB_INDEX_UNIQUE,
                        ['course_id', 'task_id']);
    $table->add_index('ix_category_id',
                        XMLDB_INDEX_NOTUNIQUE,
                        ['category_id']);

    // Conditionally launch create table for course_category_reset.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Categorytasksextender savepoint reached.
    upgrade_plugin_savepoint(true,
                                2021091501,
                                'tool',
                                'categorytasksextender');
}