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
$string['text_backup_heading'] = 'Copia de seguridad de los cursos de: ';
$string['button_backup_proceed'] = 'Proceder';
$string['default_value_backup_path'] = 'Por favor introduzca la ruta donde almacenar las copias de seguridad...';
$string['field_text_backup_path'] = 'Carpeta para Copias de Seguridad';
$string['field_text_apply_recursiveness'] = '¿Búsqueda recursiva?';
$string['field_text_category_folder_creation'] = '¿Crear una carpeta para cada categoría?';
$string['field_text_run_date_time'] = 'Fecha/Hora de Ejecución';
$string['error_message_backup_path_not_exist'] = 'La carpeta para las copias de seguridad no existe: ';
$string['field_text_backup_path_help'] = 'Especifique la carpeta en la cual se almacenarán los archivos de copia de seguridad dentro del servidor que Moodle se encuentra.';
$string['field_text_apply_recursiveness_help'] = 'Especifique si desea o no que se busquen subcategorías de manera recursiva.';
$string['field_text_category_folder_creation_help'] = 'Especifique si desea o no que la tarea crée una carpeta para almacenar las copias de seguridad de cada categoría.';
$string['field_text_run_date_time_help'] = 'Especifique la fecha/hora en que desea ejecutar esta tarea en caso que no requira se ejecutada ahora.';
$string['message_info_backup_task_queue'] = 'Una Tarea Adhoc para Copias de seguridad ha sigo programada para la categoría <strong>{$a}</strong>.';
$string['error_message_backup_path_not_writeable'] = 'La ruta para el almacenamiento de las copias de seguridad <strong>({$a})</strong> no permite la escritura.';