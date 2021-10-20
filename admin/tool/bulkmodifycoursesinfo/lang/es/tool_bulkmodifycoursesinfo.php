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
 * @package   tool_bulkmodifycoursesinfo
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['pluginname'] = 'Modificación Info Cursos por Lote';
$string['heading_text_bulk_modify_courses'] = 'Modificar la información de los cursos del archivo';
$string['message_info_bulk_modify_courses_task'] = 'Se programó una tarea para la modificación de los cursos contenidos en el archivo {$a->file_name}. <br />Id de la tarea: <strong>{$a->task_id}</strong>';
$string['message_info_not_admin_user'] = 'No está autorizado a visualizar esta página debido a que no es un usuario administrador.';
$string['field_text_courses_modifications_file'] = 'Archivo de Modificaciones de Cursos';
$string['field_text_run_date_time'] = 'Fecha/Hora de Ejecución';
$string['field_text_example_file_download'] = 'Descarga un archivo de ejemplo';
$string['button_text_proceed'] = 'Proceder';
$string['field_text_courses_modifications_file_help'] = 'Seleccione el archivo que contiene las informaciones de modificaciones de los cursos.';
$string['field_text_run_date_time_help'] = 'Especifique la fecha/hora en que desea ejecutar esta tarea en caso que no requira se ejecutada ahora.';
$string['field_text_example_file_download_help'] = 'Este archivo le sirve como ejemplo de los campos que debe enviar junto con la información de los cursos a modificar. <br /><br />Este archivo contiene dos (2) ejemplos que pueden ser eliminados.';
$string['message_error_courses_modifications_file_not_given'] = 'El campo <strong>{$a}</strong> es requerido.';
$string['message_error_courses_modifications_file_wrong_format'] = 'El archivo <strong>{$a->file_name}</strong> posee un tipo de archivo incorrecto (<strong>{$a->file_type}</strong>).';
$string['message_error_courses_modifications_file_missing_fields'] = 'Existen algunos campos faltantes <strong>({$a->fields})</strong> en el archivo <strong>{$a->file_name}</strong>.';
$string['message_error_courses_modifications_file_missing_courses_modifications'] = 'El archivo <strong>({$a->file_name})</strong> no contiene ninguna modificación de cursos.';
$string['message_error_courses_modifications_file_lines_with_errors'] = 'El archivo <strong>({$a->file_name})</strong> contiene líneas de modificaciones de cursos incompletas. <strong>(Líneas: {$a->lines_with_error})</strong>';