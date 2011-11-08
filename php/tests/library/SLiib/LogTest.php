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
 * @package    SLiib
 * @subpackage UnitTests
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */
require_once 'SLiib/Log.php';

/**
 * Test class for SLiib_Log.
 * Generated by PHPUnit on 2011-10-11 at 23:52:53.
 *
 * @package    SLiib
 * @subpackage UnitTests
 */
class SLiib_LogTest extends PHPUnit_Framework_TestCase
{

    /**
     * Objet de test
     * @var SLiib_Log $_object
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
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_filename       = 'files/LogTest.log';
        $this->_testFormat     = '[%T][%d]%m';
        $this->_testLongFormat = '[%T] [%d %t] [%U] [%@] %m';

        $this->_object = new SLiib_Log($this->_filename, TRUE);

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
     * @return void
     */
    public function testLog()
    {
        $this->_object->setFormat('%m');
        $this->assertFileExists($this->_filename);
        $text = 'w000t from SLiib_LogTest';

        $this->_object->log($text, SLiib_Log::INFO, FALSE);

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
     * Test écriture dans fichier sans les permissions
     *
     * @return void
     */
    public function testWriteFailure()
    {
        $file = 'files/unwritable.log';
        chmod($file, 0444);

        try {
            $log = new SLiib_Log($file);
            $log->debug('not permit to write');
        } catch (SLiib_Log_Exception $e) {
            $this->assertInstanceOf('SLiib_Log_Exception', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test d'affichage pour les couleur
     *
     * @return void
     */
    public function testColor()
    {
        $this->_object->setFormat($this->_testLongFormat);
        $this->_object->debug('Log DEBUG, blue color ?', TRUE);
        $this->_object->warn('Log WARN, yellow color ?', TRUE);
        $this->_object->error('Log ERROR, red color ?', TRUE);
        $this->_object->crit('Log CRIT, red color ?', TRUE);
        $this->_object->info('Log INFO, no color ?', TRUE);

    }


    /**
     * Test with server Infos
     *
     * @return void
     */
    public function testWithServerInfo()
    {
        $GLOBALS['_SERVER'];
        $_SERVER['REMOTE_ADDR']     = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'w00tw00t';

        $this->_object->setFormat($this->_testLongFormat);
        $this->_object->log('fooo');

    }


}