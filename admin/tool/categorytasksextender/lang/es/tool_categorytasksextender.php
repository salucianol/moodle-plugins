<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 
/**
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Tareas Extensivas de Categorías';
$string['link_text_backup'] = 'Copias de seguridad desde';
$string['link_text_restore'] = 'Restaurar cursos hacia';
$string['link_text_reset'] = 'Reiniciar cursos de';
$string['categorytasksextender:backupcategorycourses'] = 'Crear copias de seguridad de cursos de categoría';
$string['categorytasksextender:restorecoursescategory'] = 'Restaurar copias de seguridad de cursos hacia categoría';
$string['categorytasksextender:resetcoursescategory'] = 'Reiniciar todos los cursos en una categoría';
$string['text_backup_heading'] = 'Copia de seguridad de los cursos de: <strong>{$a}</strong>';
$string['text_reset_heading'] = 'Reiniciar los cursos de: <strong>{$a}</strong>';
$string['text_restore_heading'] = 'Restaurar cursos hacia: <strong>{$a}</strong>';
$string['button_backup_proceed'] = 'Proceder';
$string['default_value_backup_path'] = 'Por favor introduzca la ruta donde almacenar las copias de seguridad...';
$string['default_value_restore_files_path'] = 'Por favor introduzca la ruta donde encontrar los archivos para restaurar...';
$string['field_text_backup_path'] = 'Carpeta para Copias de Seguridad';
$string['field_text_apply_recursiveness'] = '¿Búsqueda recursiva?';
$string['field_text_category_folder_creation'] = '¿Crear una carpeta para cada categoría?';
$string['field_text_run_date_time'] = 'Fecha/Hora de Ejecución';
$string['field_text_reset_start_date'] = 'Fecha de inicio para cursos reiniciados';
$string['field_text_reset_end_date'] = 'Fecha de finalización para cursos reiniciados';
$string['field_text_restore_files_path'] = 'Ruta de archivos a restaurar';
$string['field_text_backup_path_help'] = 'Especifique la carpeta en la cual se almacenarán los archivos de copia de seguridad dentro del servidor que Moodle se encuentra.';
$string['field_text_apply_recursiveness_help'] = 'Especifique si desea o no que se busquen subcategorías de manera recursiva.';
$string['field_text_category_folder_creation_help'] = 'Especifique si desea o no que la tarea crée una carpeta para almacenar las copias de seguridad de cada categoría.';
$string['field_text_run_date_time_help'] = 'Especifique la fecha/hora en que desea ejecutar esta tarea en caso que no requira se ejecutada ahora.';
$string['field_text_reset_start_date_help'] = 'Especifique la fecha de inicio a colocar a los cursos que están siendo reiniciados.';
$string['field_text_reset_end_date_help'] = 'Especifique la fecha de finalización a colocar a los cursos que están siendo reiniciados.';
$string['field_text_restore_files_path_help'] = 'Especifique la ruta donde se encuentran los archivos para restaurar dentro del servidor que Moodle se encuentra.';
$string['message_info_backup_task_queue'] = 'Una Tarea Adhoc para Copias de seguridad ha sigo programada para la categoría <strong>{$a}</strong>.';
$string['message_info_reset_task_queue'] = 'Una Tarea Adhoc para Reiniciar cursos ha sido programada para la categoría <strong>{$a}</strong>.';
$string['message_error_not_courses_found'] = 'No se encontraron cursos en la  categoría <strong>{$a}</strong> ni en sus subcategorías.';
$string['message_info_restore_task_queue'] = 'Una Tarea Adhoc para Restaurar cursos ha sido programada para la categoría <strong>{$a}</strong>.';
$string['error_message_backup_path_not_exist'] = 'La carpeta para las copias de seguridad no existe: ';
$string['error_message_backup_path_not_writeable'] = 'La ruta para el almacenamiento de las copias de seguridad <strong>({$a})</strong> no permite la escritura.';
$string['error_message_end_date_before_start_date'] = 'La <strong>Fecha de finalización</strong> no puede ser ajustada a un valor antes de la <strong>Fecha de inicio</strong>.';
$string['error_message_restore_files_path_not_exist'] = 'La <strong>ruta</strong> dada donde se encuentran los <strong>archivos para restuarar</strong> no existe: <strong>{$a}</strong>.';
$string['error_message_restore_files_path_not_readable'] = 'La ruta dada <strong>({$a})</strong> donde se encuentran los archivos para restaurar no puede ser leída/accedida.';
$string['error_message_restore_files_path_is_empty'] = 'La ruta dada (<strong>{$a}</strong>) donde se encuentran los archivos para resturar no contiene ningún archivo dentro ni en ninguno de sus carpeta internas.';
$string['header_task_settings'] = 'Configuración para la tarea';
$string['header_reset_settings'] = 'Configuración para reinicio';