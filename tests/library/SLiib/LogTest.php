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
 * Test class for \SLiib\Log.
 * Generated by PHPUnit on 2011-10-11 at 23:52:53.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class LogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Objet de test
     * @var \SLiib\Log
     */
    protected $_object;

    /**
     * Nom du fichier
     * @var string
     */
    protected $_filename;

    /**
     * Format de test
     * @var string
     */
    protected $_testFormat;

    /**
     * Format de test long
     * @var string
     */
    protected $_testLongFormat;

    /**
     * stdout resource
     * @var resource
     */
    public static $stdout;

    /**
     * stderr resource
     * @var resource
     */
    public static $stderr;

    /**
     * Set stdout & stderr resources
     *
     * @return void
     */
    public static function setResources()
    {
        self::$stdout = fopen('php://stdout', 'r+');
        self::$stderr = fopen('php://stderr', 'r+');
    }

    /**
     * Close stdout & stderr resources
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        fclose(self::$stdout);
        fclose(self::$stderr);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_filename       = TEST_PATH . '/files/LogTest.log';
        $this->_testFormat     = '[%T][%d]%m';
        $this->_testLongFormat = '[%T] [%d %t] [%U] [%@] %m';

        $this->_object = new Log($this->_filename, true);

        /*
         * Purge global variable stdout and stderr
         * See \Tools\StreamWrapper::stream_read() for more informations
         */
        fread(STDOUT, 1337);
        fread(STDERR, 1337);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown()
    {
        unlink($this->_filename);
        unset($this->_object);
    }

    /**
     * Test Get/Set Format method
     *
     * @covers \SLiib\Log::setFormat
     * @covers \SLiib\Log::getFormat
     *
     * @return void
     */
    public function testSetGetFormat()
    {
        $format = $this->_object->getFormat();
        $this->assertEquals('[%d %t] [%T] - %m', $format);

        $this->_object->setFormat($this->_testFormat);
        $format = $this->_object->getFormat();
        $this->assertEquals($this->_testFormat, $format);
    }

    /**
     * Test write log
     *
     * @covers \SLiib\Log::write
     * @covers \SLiib\Log::<private>
     *
     * @return void
     */
    public function testLog()
    {
        $this->_object->setFormat('%m');
        $this->assertFileExists($this->_filename);
        $text = 'w000t from \SLiib\LogTest';

        $this->_object->write($text, Log::INFO, false);

        $this->assertEquals(
            $text,
            str_replace(
                array(
                 "\r",
                 "\n",
                ),
                '',
                file_get_contents($this->_filename)
            )
        );
    }

    /**
     * Test write in file without perm
     *
     * @return void
     */
    public function testWriteFailure()
    {
        $file = TEST_PATH . '/files/unwritable.log';
        chmod($file, 0444);

        $this->setExpectedException('\SLiib\Log\Exception');
        $log = new Log($file);
    }

    /**
     * Test print string in stdout/stderr without color
     *
     * @return void
     */
    public function testPrintWithoutColor()
    {
        $this->_object->setFormat($this->_testLongFormat);
        $this->_object->debug('DEBUG', true);
        $this->_object->warn('WARN', true);
        $this->_object->error('ERROR', true);
        $this->_object->crit('CRIT', true);
        $this->_object->info('INFO', true);

        $this->assertEquals("string(5) \"DEBUG\"\n\nINFO\n", $GLOBALS['stdout']);
        $this->assertEquals("WARN\nERROR\nCRIT\n", $GLOBALS['stderr']);
    }

    /**
     * Test print string in stdout/stderr with color
     *
     * @return void
     */
    public function testPrintWithColor()
    {
        $this->_object->setFormat($this->_testLongFormat)->setColor(true);
        $this->_object->debug('DEBUG', true);
        $this->_object->warn('WARN', true);
        $this->_object->error('ERROR', true);
        $this->_object->crit('CRIT', true);
        $this->_object->info('INFO', true);

        $this->assertEquals(
            "\033[34mstring(5) \"DEBUG\"\n\033[0m\n\033[0mINFO\033[0m\n",
            $GLOBALS['stdout']
        );
        $this->assertEquals(
            "\033[33mWARN\033[0m\n\033[31mERROR\033[0m\n\033[31mCRIT\033[0m\n",
            $GLOBALS['stderr']
        );
    }

    /**
     * Test with server Infos
     *
     * @covers \SLiib\Log::write
     * @covers \SLiib\Log::<private>
     *
     * @return void
     */
    public function testWithServerInfo()
    {
        $GLOBALS['_SERVER'];
        $_SERVER['REMOTE_ADDR']     = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'w00tw00t';

        $this->_object->setFormat($this->_testLongFormat);
        $this->_object->write('fooo');
    }
}

//Redefine STDOUT & STDERR resources
if (in_array('php', stream_get_wrappers())) {
    stream_wrapper_unregister('php');
}

stream_wrapper_register('php', '\Tools\StreamWrapper');

LogTest::setResources();

define('TMP_STDOUT', LogTest::$stdout);
define('TMP_STDERR', LogTest::$stderr);
const STDOUT = TMP_STDOUT;
const STDERR = TMP_STDERR;

