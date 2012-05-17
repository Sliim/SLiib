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
     * String test
     * @var string
     */
    private $_string = 'foo';

    /**
     * Integer test
     * @var int
     */
    private $_int = 1337;


    /**
     * Dump test with php sapi equal cli
     *
     * @return void
     */
    public function testDumpCli()
    {
        \Stubs\Sapi::setSapi('cli');

        $expected = 'string(' . strlen($this->_string) . ') "' . $this->_string . '"';

        $this->expectOutputString($expected . PHP_EOL);
        $dump = Debug::dump($this->_string);
        $this->assertStringMatchesFormat(
            $expected,
            $dump
        );

        $this->assertStringStartsNotWith('<pre>', $dump);
        $this->assertStringEndsNotWith('</pre>', $dump);

        $dump = Debug::dump($this->_int, FALSE);
        $this->assertStringMatchesFormat('int(' . $this->_int . ')', $dump);
        $this->assertStringStartsNotWith('<pre>', $dump);
        $this->assertStringEndsNotWith('</pre>', $dump);

    }


    /**
     * Dump test with php sapi equal 'apache'
     *
     * @return void
     */
    public function testDumpApache()
    {
        \Stubs\Sapi::setSapi('apache');

        $dump = Debug::dump($this->_int, FALSE);

        $this->assertStringStartsWith('<pre>', $dump);
        $this->assertStringEndsWith('</pre>', $dump);

    }


    /**
     * Test force add <pre> tags
     *
     * @return void
     */
    public function testDisallowPreTagsForAnySapiValue()
    {
        \Stubs\Sapi::setSapi('cli');

        $dump = Debug::dump($this->_int, FALSE, FALSE);
        $this->assertStringStartsNotWith('<pre>', $dump);
        $this->assertStringEndsNotWith('</pre>', $dump);

    }


}