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
 * @subpackage UnitTests_-_Stubs
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace Stubs;

/**
 * Test class for \Stubs\SolR.
 * Generated by PHPUnit on 2012-01-08 at 17:45:53.
 *
 * @package    Tests
 * @subpackage UnitTests_-_Stubs
 */
class SolRTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        if (!SolR::installed()) {
            $this->markTestSkipped('No stubs installed.');
        }

        if (!SolR::jvmAvailable()) {
            $this->markTestSkipped('No JVM available.');
        }
    }

    /**
     * Test stub installed
     *
     * @covers \Stubs\SolR::installed
     *
     * @return void
     */
    public function testInstalled()
    {
        rename(STUBS_PATH . '/SolR/start.jar', STUBS_PATH . '/SolR/start.jar.backup');
        $this->assertFalse(SolR::installed());

        rename(STUBS_PATH . '/SolR/start.jar.backup', STUBS_PATH . '/SolR/start.jar');
        $this->assertTrue(SolR::installed());
    }

    /**
     * Test JVM available
     *
     * @covers \Stubs\SolR::jvmAvailable
     *
     * @return void
     */
    public function testJvmAvailable()
    {
        $this->assertTrue(SolR::jvmAvailable());
    }

    /**
     * Test stub started
     *
     * @covers \Stubs\SolR::started
     *
     * @return void
     */
    public function testStarted()
    {
        $this->assertFalse(SolR::started());

        SolR::start();
        $this->assertTrue(SolR::started());
        SolR::stop();
    }

    /**
     * Test start stub
     *
     * @covers \Stubs\SolR::start
     *
     * @return void
     */
    public function testStart()
    {
        $this->assertTrue(SolR::start());
        $this->assertTrue(SolR::start());
    }

    /**
     * Test stop stub
     *
     * @covers \Stubs\SolR::stop
     *
     * @return void
     */
    public function testStop()
    {
        SolR::start();
        $this->assertTrue(SolR::stop());

        $reflection = new \ReflectionClass('\Stubs\SolR');

        $pid = $reflection->getProperty('_pid');
        $pid->setAccessible(true);
        $pid->setValue(9999999999999999999);

        $this->assertFalse(SolR::stop());
    }
}

