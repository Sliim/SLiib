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
 * Test class for \SLiib\WebApp\Security\Checker\FilenamePolicy.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class FilenamePolicyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test object
     * @var \SLiib\WebApp\Security\Checker\FilenamePolicy
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
        $this->object = new FilenamePolicy();
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
     * @covers \SLiib\WebApp\Security\Checker\FilenamePolicy::run
     * @covers \SLiib\WebApp\Security\Model\NegativeSecurity
     *
     * @return void
     */
    public function testRun()
    {
        \Tools\Request::setRequestUri('/index/index');
        Request::init();

        $result = $this->object->run();
        $this->assertTrue($result);
    }

    /**
     * Test run with forbidden extension filename in request URI
     *
     * @return void
     */
    public function testRunWithForbiddenExtensionFilename()
    {
        $this->setExpectedException('\SLiib\WebApp\Security\Exception\HackingAttempt');

        \Tools\Request::setRequestUri('/dumps/db.sql');
        Request::init();

        $this->object->run();
    }

    /**
     * Test run with forbidden filename in request URI
     *
     * @return void
     */
    public function testRunWithForbiddenFilename()
    {
        $this->setExpectedException('\SLiib\WebApp\Security\Exception\HackingAttempt');

        \Tools\Request::setRequestUri('../../../../etc/passwd');
        Request::init();

        $this->object->run();
    }
}
