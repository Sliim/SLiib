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
 * PHP Version 5.3
 *
 * @category   SLiib
 * @package    Tests
 * @subpackage UnitTests
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp\Security\Checker;

use SLiib\WebApp\Security\Exception;
use SLiib\WebApp\Request;

/**
 * Test class for \SLiib\WebApp\Security\Checker\BadRobots.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class BadRobotsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test object
     * @var \SLiib\WebApp\Security\Checker\BadRobots
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_object = new BadRobots();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->_object);
    }

    /**
     * Test run
     *
     * @covers \SLiib\WebApp\Security\Checker\BadRobots::run
     * @covers \SLiib\WebApp\Security\Model\NegativeSecurity
     *
     * @return void
     */
    public function testRun()
    {
        \Tools\Request::setUserAgent('foo');
        Request::init();

        $result = $this->_object->run();
        $this->assertTrue($result);
    }

    /**
     * Test run with bad robots
     *
     * @return void
     */
    public function testRunWithBadRobots()
    {
        $this->setExpectedException('\SLiib\WebApp\Security\Exception\HackingAttempt');

        \Tools\Request::setUserAgent('DirBuster-0.12');
        Request::init();

        $this->_object->run();
    }

    /**
     * Test run with Nikto scanner
     *
     * @return void
     */
    public function testRunWithNiktoScanner()
    {
        $this->setExpectedException('\SLiib\WebApp\Security\Exception\HackingAttempt');

        \Tools\Request::setUserAgent('Mozilla/4.75 (Nikto/2.1.4) (Test:map_codes)');
        Request::init();

        $this->_object->run();
    }
}

