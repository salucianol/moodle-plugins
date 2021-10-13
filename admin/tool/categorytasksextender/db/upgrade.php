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
 * Method for upgrading the plugin.
 * 
 * @package   tool_categorytasksextender
 * @copyright 2021, Samuel Luciano <sa.lassis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function xmldb_tool_categorytasksextender_upgrade($oldversion){
    global $DB;

    $dbman = $DB->get_manager();
    
    $upgrade_dir = __DIR__.'/upgrade_versions/';
    $upgrade_versions = scandir($upgrade_dir);

    foreach ($upgrade_versions as $upgrade_version) {
        $upgrade_version_file = $upgrade_dir.$upgrade_version;

        if(is_file($upgrade_version_file) 
                && strstr($upgrade_version_file, '.php')){

            require_once($upgrade_version_file);

        }
    }

    return true;
}