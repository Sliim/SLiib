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
use SLiib\String;

/**
 * Test class for \SLiib\String.
 * Generated by PHPUnit on 2011-10-15 at 01:01:46.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class StringTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Test clean string
     *
     * @covers \SLiib\String::clean
     *
     * @return void
     */
    public function testClean()
    {
        $string = "           crade        la         \n



      string\t\t\t\t\t mouarf
  :o clair !                 ";

        $cleaned = String::clean($string);

        $this->assertLessThan(
            strlen($string),
            strlen($cleaned)
        );

        $this->assertEquals(34, strlen($cleaned));

    }


}