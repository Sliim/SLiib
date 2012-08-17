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
 * @subpackage UnitTests_-_Tools
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace Tools;

/**
 * Test class for \Tools\StreamWrapper.
 * Generated by PHPUnit on 2012-01-26 at 00:28:42.
 *
 * @package    Tests
 * @subpackage UnitTests_-_Tools
 */
class StreamWrapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Stream name for tests
     * @var string
     */
    private static $stream = 'foo';

    /**
     * Global variable name for tests
     * @var string
     */
    private static $varName = 'bar';

    /**
     * File pointer resource
     * @var resource
     */
    private $fp = null;

    /**
     * Stream register
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        if (in_array(static::$stream, stream_get_wrappers())) {
            stream_wrapper_unregister(static::$stream);
        }

        stream_wrapper_register(static::$stream, '\Tools\StreamWrapper');
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fp = fopen(static::$stream . '://' . static::$varName, 'r+');
    }

    /**
     * Test Open stream
     *
     * @covers \Tools\StreamWrapper::stream_open
     *
     * @return void
     */
    public function testStreamOpen()
    {
        $this->fp = fopen(static::$stream . '://myvar', 'r+');
        $this->assertNull($GLOBALS['myvar']);
    }

    /**
     * Test Write stream
     *
     * @covers \Tools\StreamWrapper::stream_write
     *
     * @return void
     */
    public function testStreamWrite()
    {
        fwrite($this->fp, 'w00t');
        $this->assertEquals('w00t', $GLOBALS[static::$varName]);
    }

    /**
     * Test Read stream
     *
     * @covers \Tools\StreamWrapper::stream_read
     *
     * @return void
     */
    public function testStreamRead()
    {
        fread($this->fp, 1337);
        $this->assertNull($GLOBALS[static::$varName]);
    }
}

