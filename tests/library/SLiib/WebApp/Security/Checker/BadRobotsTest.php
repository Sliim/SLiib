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

/**
 * Test class for SLiib_WebApp_Security_Checker_BadRobots.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SLiib_WebApp_Security_Checker_BadRobotsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test object
     * @var SLiib_WebApp_Security_Checker_BadRobots
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
        $this->_object = new SLiib_WebApp_Security_Checker_BadRobots();

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
     * @covers SLiib_WebApp_Security_Checker_BadRobots::run
     * @covers SLiib_WebApp_Security_Abstract_NegativeSecurityModel
     *
     * @return void
     */
    public function testRun()
    {
        Static_Request::setUserAgent('foo');
        SLiib_WebApp_Request::init();

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
        Static_Request::setUserAgent('DirBuster-0.12');
        SLiib_WebApp_Request::init();

        try {
            $this->_object->run();
        } catch (SLiib_WebApp_Security_Exception_HackingAttempt $e) {
            $this->assertInstanceOf('SLiib_WebApp_Security_Exception_HackingAttempt', $e);
            return;
        } catch (PHPUnit_Framework_Error $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run with Nikto scanner
     *
     * @return void
     */
    public function testRunWithNiktoScanner()
    {
        Static_Request::setUserAgent('Mozilla/4.75 (Nikto/2.1.4) (Test:map_codes)');
        SLiib_WebApp_Request::init();

        try {
            $this->_object->run();
        } catch (SLiib_WebApp_Security_Exception_HackingAttempt $e) {
            $this->assertInstanceOf('SLiib_WebApp_Security_Exception_HackingAttempt', $e);
            return;
        } catch (PHPUnit_Framework_Error $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


}
?>
