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

namespace block_activity_completion\tests;

defined('MOODLE_INTERNAL') || die();

global $CFG;

class block_activity_completion_test extends \advanced_testcase {

    /**
     * course
     *
     * @param object $course
     */
    protected $course;

    /**
     * page
     *
     * @param object $page
     */
    protected $page;

    /**
     * user
     *
     * @param object $user
     */
    protected $user;

    /**
     * modinfo
     *
     * @param object $modinfo
     */
    protected $modinfo;

    /**
     * Prepares things before this test case is initialised
     * @return void
     */

    public static function setUpBeforeClass() {
        global $CFG, $DB, $OUTPUT, $USER;;
        require_once($CFG->libdir . '/filelib.php');
        require_once($CFG->dirroot. "/lib/enrollib.php");
        require_once($CFG->dirroot. "/course/lib.php");
        require_once($CFG->dirroot. '/lib/completionlib.php');
        require_once($CFG->dirroot. '/course/lib.php');
    }
    /**
     * It will check block is in course context mode
     * @return array blockview
     */

    public function applicable_formats() {
        $blockview  = array(
            'course-view' => true,
            'mod' => true
        );
        return $blockview;
    }

    /**
     * It will display table format data
     * @return array tablearraydata
     * @return html table
     */
    public function display_tabledata($tablearraydata) {
        $table = new \html_table();
        $tabeled = \html_writer::table($tablearraydata);
        echo $tabeled;
    }
    /**
     * Setup testcase.
     * @return void
     */
    public function test_setup() {
        global $CFG;
        $this->resetAfterTest();

        // Setup test course.
        $this->course = $this->getDataGenerator()->create_course(array(
            'fullname' => 'Test Course One  Full Name',
            'shortname' => 'testcourseone Short Name',
            'idnumber' => 'activycompletion123',
            'enablecompletion' => 1
        ));

        // Setup test activity .
        $this->assign = $this->getDataGenerator()->create_module('assign', array('course' => $this->course->id),
            array('completion' => 2, 'completionview' => 1, 'idnumber' => 'activycompletion123'));
        $this->quiz = $this->getDataGenerator()->create_module('quiz', array('course' => $this->course->id),
            array('completion' => 2, 'completionview' => 1, 'idnumber' => 'activycompletion111', 'completionstate' => 1));

        // Setup test user.
        $this->user = self::getDataGenerator()->create_user((array(
            'id' => 123,
            'username' => 'user1',
            'firstname' => 'user1',
            'lastname' => 'user1',
            'email' => 'user1@gmail.com'
        )));

        $view = $this->applicable_formats();
        if ($view['course-view'] == true) {
            $user = $this->user;
            $userid = $user->id;
            $table = new \html_table();
            $table->head = (array) get_strings(array('cmid', 'name', 'startdate', 'status'), 'block_activity_completion');
            $modinfo = get_fast_modinfo($this->course);
            if (!empty($modinfo)) {
                foreach ($modinfo->cms as $cm) {
                    $completion = new \completion_info($this->course);
                    if (!$completion->is_enabled()) {
                        throw new moodle_exception('completionnotenabled', 'completion');
                    }
                    $completiondata = $completion->get_data($cm, false, $userid);
                    if ($cm->modname == 'quiz') {
                        $completiondata->completionstate = 1;
                    } else {
                        $completiondata->completionstate = $completiondata->completionstate;
                    }
                    if ($completiondata->completionstate == 0) { // Completion Status is added.
                        $status = '-';
                    } else {
                        $status = '<p class="btn btn-success">'.get_string('completed', 'block_activity_completion').'</p>';
                    }
                    $name = \html_writer::link(
                        new \moodle_url(
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
            }
            $this->display_tabledata($table);
        }
    }
}
