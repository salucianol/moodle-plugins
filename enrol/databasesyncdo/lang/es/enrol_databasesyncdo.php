<?php
/**
 * Enrolment data synchronization plugin version specification.
 *
 * @package    enrol_databasesyncdo
 * @copyright  2020 Samuel Luciano {@link http://koalacreativesoftware.com}
 * @license    None
 */

$string['pluginname'] = 'Sincronización de Matriculaciones para Base de Datos Externa';
$string['pluginname_desc'] = 'Este componente tiene el propósito de permitir seleccionar de manera rápida el año y período académico para matricular los usuarios usando una base de datos externa. Sirve como soporte para adicionar una tarea programada que sincronice la base de datos externa con la base de datos interna de Moodle.';
$string['academic_year_header_label'] = 'Parámetros para el Año Académico';
$string['academic_year_header_description'] = 'Por favor, seleccione las opciones de configuración para el proceso de sincronización entre las bases de datos externa e interna.';
$string['academic_year_label'] = 'Año Académico';
$string['academic_year_description'] = 'Seleccione el año académico para la sincronización. Si requiere utilizar este valor en la consulta SQL que colocará mas abajo, entonces puede usar la variable [[ACADEMIC_YEAR]] en cualquier lugar que desee que sea colocar el valor de este parámetro.';
$string['academic_period_undergradute_label'] = 'Período Académico de Grado';
$string['academic_period_undergradute_description'] = 'Seleccione el período académico de grado para la sincronización. Si requiere utilizar este valor en la consulta SQL que colocará mas abajo, entonces puede usar la variable [[UNDERGRADUATE_PERIOD]] en cualquier lugar que desee que sea colocar el valor de este parámetro.';
$string['academic_use_same_period_label'] = 'Es el período de grado el mismo que de posgrado?';
$string['academic_use_same_period_description'] = 'Seleccione si el período académico de grado es el mismo que para posgrado o no en su institución.';
$string['academic_period_postgradute_label'] = 'Período Académico de Posgrado';
$string['academic_period_postgradute_description'] = 'Seleccione el período académico de posgrado para la sincronización. Si requiere utilizar este valor en la consulta SQL que colocará mas abajo, entonces puede usar la variable [[POSTGRADUATE_PERIOD]] en cualquier lugar que desee que sea colocar el valor de este parámetro.';

$string['external_database_header_label'] = 'Configuraciones de la Base de Datos Externa';
$string['external_database_header_description'] = 'Coloque las informaciones de la base de datos externa para el proceso de sincronización.';
$string['external_database_engine_label'] = 'Motor de Base de Datos Externa';
$string['external_database_engine_description'] = 'Seleccione el motor de base de datos para la conexión con la base de datos externa.';
$string['external_database_dbhost_label']= 'Servidor de Base de Datos Externa';
$string['external_database_dbhost_description'] = 'Coloque el nombre del servidor donde se localiza la base de datos externa. Para especificar el puerto, utilice la notación de :. Ejemplo: <nombre_servidor>:<numero_puerto>.';
$string['external_database_dbname_label'] = 'Nombre de la Base de Datos Externa';
$string['external_database_dbname_description'] = 'Coloque el nombre de la base de datos externa que será utilizada para la búsqueda de las matriculaciones.';
$string['external_database_dbuser_label'] = 'Usuario de la Base de Datos Externa';
$string['external_database_dbuser_description'] = 'Coloque el nombre del usuario de base de datos que será utilizado para la autenticación.';
$string['external_database_dbpass_label'] = 'Contraseña de la Base de Datos Externa';
$string['external_database_dbpass_description'] = 'Coloque la contraseña de la base de datos externa que será utilizada para la autenticación.';

$string['internal_database_header_label'] = 'Configuraciones de la Base de Datos Interna';
$string['internal_database_header_description'] = 'Coloque las informaciones de la base de datos interna para el proceso de sincronización.';
$string['internal_database_engine_label'] = 'Motor de Base de Datos Interno';
$string['internal_database_engine_description'] = 'Seleccione el motor de base de datos para la conexión con la base de datos interna.';
$string['internal_database_dbhost_label']= 'Servidor de Base de Datos Interna';
$string['internal_database_dbhost_description'] = 'Coloque el nombre del servidor donde se localiza la base de datos interna. Para especificar el puerto, utilice la notación de :. Ejemplo: <nombre_servidor>:<numero_puerto>.';
$string['internal_database_dbname_label'] = 'Nombre de la Base de Datos Interna';
$string['internal_database_dbname_description'] = 'Coloque el nombre de la base de datos interna que será utilizada para la búsqueda de las matriculaciones.';
$string['internal_database_dbuser_label'] = 'Usuario de la Base de Datos Interna';
$string['internal_database_dbuser_description'] = 'Coloque el nombre del usuario de base de datos interna que será utilizado para la autenticación.';
$string['internal_database_dbpass_label'] = 'Contraseña de la Base de Datos Interna';
$string['internal_database_dbpass_description'] = 'Coloque la contraseña de la base de datos interna que será utilizada para la autenticación.';
$string['internal_database_course_table_label'] = 'Tabla para los Cursos';
$string['internal_database_course_table_description'] = 'Coloque el nombre de la tabla que será utilizado para la inserción de los cursos.';
$string['internal_database_enrolments_table_label'] = 'Tabla para las Matriculaciones';
$string['internal_database_enrolments_table_description'] = 'Coloque el nombre de la tabla que será utilizado para las inserciones de las matriculaciones.';

$string['queries_header_label'] = 'Consultas SQL para sincrocnización';
$string['queries_header_description'] = 'Configure las consulta de SQL que serán utilizadas en la base de datos externa.';
$string['enrolments_query_label'] = 'Consulta SQL para Matriculaciones';
$string['enrolments_query_description'] = 'Coloque la consulta SQL que será utilizada para la carga de las matriculaciones de los usuarios desde la base de datos externa.';
$string['courses_query_label'] = 'Consulta SQL para Cursos';
$string['courses_query_description'] = 'Coloque la consulta SQL que será utilizada para la carga de los cursos desde la base de datos externa.';

$string['matching_fields_header_label'] = 'Emparejamiento de Campos';
$string['matching_fields_header_description'] = 'Coloque los nombres de los campos de la Base de Datos Externa que serán emparejados con los campos de la Base de Datos Interna durante el proceso de sincronización.';
$string['matching_fields_username_label'] = 'Campo de Usuario';
$string['matching_fields_username_description'] = 'Coloque el nombre del campo que empareja con el campo Usuario.';
$string['matching_fields_role_label'] = 'Campo de Rol';
$string['matching_fields_role_description'] = 'Coloque el nombre del campo que empareja con el campo Rol.';
$string['matching_fields_course_idnumber_label'] = 'Campo de Numero de ID';
$string['matching_fields_course_idnumber_description'] = 'Coloque el nombre del campo que empareja con el campo Numero de ID.';
$string['matching_fields_course_shortname_label'] = 'Campo de Nombre Corto';
$string['matching_fields_course_shortname_description'] = 'Coloque el nombre del campo que empareja con el campo Nombre Corto.';
$string['matching_fields_course_fullname_label'] = 'Campo de Nombre Completo';
$string['matching_fields_course_fullname_description'] = 'Coloque el nombre del campo que empareja con el campo Nombre Completo.';
$string['matching_fields_course_category_label'] = 'Campo de Categoría';
$string['matching_fields_course_category_description'] = 'Coloque el nombre del campo que empareja con el campo Categoría.';

$string['privacy:metadata'] = 'Este componente no almacena ninguna información del usuario.';
$string['sync_enrolments_database_do:scheduled_task:name'] = 'Tarea de Sincronización de Matriculaciones para Base de Datos Externa';