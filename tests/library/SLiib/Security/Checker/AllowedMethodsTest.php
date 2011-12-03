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
 * Test class for SLiib_Security_Checker_AllowedMethods.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SLiib_Security_Checker_AllowedMethodsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test object
     * @var SLiib_Security_Checker_AllowedMethods
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
        $this->_object = new SLiib_Security_Checker_AllowedMethods();

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
     * @return void
     */
    public function testRun()
    {
        Static_Request::setServerInfo('REQUEST_METHOD', 'GET');
        SLiib_HTTP_Request::init();

        $result = $this->_object->run();
        $this->assertTrue($result);

    }


    /**
     * Test run with forbidden http method
     *
     * @return void
     */
    public function testRunWithForbiddenHTTPMethod()
    {
        Static_Request::setServerInfo('REQUEST_METHOD', 'WOOT');
        SLiib_HTTP_Request::init();

        try {
            $this->_object->run();
        } catch (SLiib_Security_Exception_HackingAttempt $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_HackingAttempt', $e);
            return;
        } catch (PHPUnit_Framework_Error $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


}
?>
