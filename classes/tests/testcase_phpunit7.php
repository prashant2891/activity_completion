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

/**
 * Compatibility shim for older PHPunit versions.
 *
 * @package    block_activity_completion
 */
abstract class testcase_phpunit7 extends \advanced_testcase {
// @codingStandardsIgnoreStart
    /**
     * See PHPUnit\Framework\TestCase::setUp().
     */
    protected function setUp() {
        $this->set_up();
    }
// @codingStandardsIgnoreEnd
}
