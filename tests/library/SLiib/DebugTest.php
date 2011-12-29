<?php
/**
 * This source file is part of SLiib.
 *
 * SLiib is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SLiib is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with SLiib. If not, see <http://www.gnu.org/licenses/gpl-3.0.html>.
 *
 * PHP version 5
 *
 * @category   SLiib
 * @package    Tests
 * @subpackage UnitTests
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * Test class for \SLiib\Debug.
 * Generated by PHPUnit on 2011-12-17 at 20:26:57.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class DebugTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Dump test
     *
     * @covers \SLiib\Debug::dump
     *
     * @return void
     */
    public function testDump()
    {
        $string = 'foo';
        $int    = 1337;

        $dump = Debug::dump($string);
        $this->assertStringMatchesFormat('string(3) "foo"', $dump);
        $this->assertStringStartsNotWith('<pre>', $dump);
        $this->assertStringEndsNotWith('</pre>', $dump);

        $dump = Debug::dump($int, FALSE, TRUE);
        $this->assertStringStartsWith('<pre>', $dump);
        $this->assertStringEndsWith('</pre>', $dump);

    }


}
?>
