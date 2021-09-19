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
 * Handles User Activity Completion Plugin.
 *
 * @package block_activity_completion
 * @author  Prashant Yallatti<prashantyallatti91@gmail.com>
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');

class block_activity_completion extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_activity_completion');
    }
    /**
     * Core function, specifies where the block can be used.
     * @return array
     */
    public function applicable_formats() {
        return array('course-view' => true, 'mod' => true);
    }
    public function get_content() {
        global $CFG, $DB, $OUTPUT, $USER;
        require_once($CFG->dirroot. "/lib/enrollib.php");
        require_once($CFG->dirroot . "/course/lib.php");
        require_once("$CFG->libdir/gradelib.php");
        require_once("$CFG->dirroot/grade/querylib.php");
        require_once($CFG->dirroot. "/lib/completionlib.php");
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $course = $this->page->course;
        require_once($CFG->dirroot.'/course/lib.php');
        $modinfo = get_fast_modinfo($course);
        $x = '';
        $userid = $USER->id;
        $table = new html_table();
        $table->head = (array) get_strings(array('cmid', 'name', 'startdate', 'status'), 'block_activity_completion');
        foreach ($modinfo->cms as $cm) {
            // Set up completion object and check it is enabled.
            $completion = new completion_info($course);
            if (!$completion->is_enabled()) {
                throw new moodle_exception('completionnotenabled', 'completion');
            }
            $completiondata = $completion->get_data($cm, false, $userid);
            if ($completiondata->completionstate == 0) { // Completion Status is added.
                $status = '-';
            } else {
                $status = '<p class="btn btn-success">'.get_string('completed', 'block_activity_completion').'</p>';
            }
            $name = html_writer::link(
                new moodle_url(
                    $CFG->wwwroot.'/mod/'.$cm->modname.'/view.php',
                    array('id' => $cm->id)
                ),
                "$cm->name", array('class' => 'btn btn-sm btn-primary text-color')
            );
            $table->data[] = array(
                $completiondata->coursemoduleid,
                $name,
                date('d-M-Y', $cm->added),
                $status
            );
        }
        $tabeled = html_writer::table($table);
        $this->content->text = html_writer::div($tabeled, null, array('id' => 'table12'));
        $this->content->footer = '';
        return $this->content;
    }
}


