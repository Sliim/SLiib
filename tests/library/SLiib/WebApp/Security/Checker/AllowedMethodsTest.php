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
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->object = new AllowedMethods();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->object);
    }

    /**
     * Test run
     *
     * @return void
     */
    public function testRun()
    {
        \Tools\Request::setRequestMethod('GET');
        Request::init();

        $result = $this->object->run();
        $this->assertTrue($result);
    }

    /**
     * Test run with forbidden http method
     *
     * @return void
     */
    public function testRunWithForbiddenHttpMethod()
    {
        $this->setExpectedException('\SLiib\WebApp\Security\Exception\HackingAttempt');

        \Tools\Request::setRequestMethod('WOOT');
        Request::init();

        $this->object->run();
    }
}
