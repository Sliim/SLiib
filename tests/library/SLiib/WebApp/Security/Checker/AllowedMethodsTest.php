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
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp\Security\Checker;
use SLiib\WebApp\Security\Exception,
    SLiib\WebApp\Request;

/**
 * Test class for \SLiib\WebApp\Security\Checker\AllowedMethods.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class AllowedMethodsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test object
     * @var \SLiib\WebApp\Security\Checker\AllowedMethods
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return \void
     */
    public function setUp()
    {
        $this->_object = new AllowedMethods();

    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return \void
     */
    public function tearDown()
    {
        unset($this->_object);

    }


    /**
     * Test run
     *
     * @return \void
     */
    public function testRun()
    {
        \Tools\Request::setRequestMethod('GET');
        Request::init();

        $result = $this->_object->run();
        $this->assertTrue($result);

    }


    /**
     * Test run with forbidden http method
     *
     * @return \void
     */
    public function testRunWithForbiddenHTTPMethod()
    {
        \Tools\Request::setRequestMethod('WOOT');
        Request::init();

        try {
            $this->_object->run();
        } catch (Exception\HackingAttempt $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\HackingAttempt', $e);
            $this->assertInstanceOf('\SLiib\IException\Runtime', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


}
?>
