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

function tool_categorytasksextender_extend_navigation_category_settings(navigation_node $parentnode, context_coursecat $context){
    global $CFG;
    
    $categoryid = $context->instanceid;
    if(has_capability('tool/categorytasksextender:backupcategorycourses', $context)){
        $linktext = get_string('link_text_backup', 
                                'tool_categorytasksextender');
        $link = new moodle_url($CFG->wwwroot.'/admin/tool/categorytasksextender/backup.php',
                                array('categoryid'=>$categoryid));
        $node = navigation_node::create($linktext, 
                                            $link, 
                                            navigation_node::TYPE_SETTING, 
                                            null, 
                                            null, 
                                            new pix_icon('i/backup', ''));
        $parentnode->add_node($node);
    }
    if(has_capability('tool/categorytasksextender:restorecoursescategory', $context)){
        $linktext = get_string('link_text_restore', 
                                'tool_categorytasksextender');
        $link = new moodle_url($CFG->wwwroot.'/admin/tool/categorytasksextender/restore.php',
                                array('categoryid'=>$categoryid));
        $node = navigation_node::create($linktext, 
                                            $link, 
                                            navigation_node::TYPE_SETTING, 
                                            null, 
                                            null, 
                                            new pix_icon('i/restore', ''));
        $parentnode->add_node($node);
    }
    if(has_capability('tool/categorytasksextender:resetcoursescategory', $context)){
        $linktext = get_string('link_text_reset', 
                                'tool_categorytasksextender');
        $link = new moodle_url($CFG->wwwroot.'/admin/tool/categorytasksextender/reset.php',
                                array('categoryid'=>$categoryid));
        $node = navigation_node::create($linktext, 
                                            $link, 
                                            navigation_node::TYPE_SETTING, 
                                            null, 
                                            null, 
                                            new pix_icon('t/reset', ''));
        $parentnode->add_node($node);
    }
    if(has_capability('tool/categorytasksextender:removecoursescategory', $context)){
        $linktext = get_string('link_text_remove', 
                                'tool_categorytasksextender');
        $link = new moodle_url($CFG->wwwroot.'/admin/tool/categorytasksextender/remove.php',
                                array('categoryid'=>$categoryid));
        $node = navigation_node::create($linktext, 
                                            $link, 
                                            navigation_node::TYPE_SETTING, 
                                            null, 
                                            null, 
                                            new pix_icon('i/trash', ''));
        $parentnode->add_node($node);
    }
}