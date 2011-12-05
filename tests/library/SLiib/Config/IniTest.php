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
 * Test class for SLiib_Config_Ini.
 * Generated by PHPUnit on 2011-10-12 at 00:53:28.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SLiib_Config_IniTest extends PHPUnit_Framework_TestCase
{

    /**
     * Objet de test
     * @var SLiib_Config_Ini
     */
    protected $_object;

    /**
     * Fichier .ini de test
     * @var string
     */
    protected $_iniFile;

    /**
     * Not exists file
     * @var string
     */
    protected $_iniFail;

    /**
     * Bad section definition
     * @var string
     */
    protected $_iniBadSection;

    /**
     * No parent section
     * @var string
     */
    protected $_iniNoParent;

    /**
     * Directive definition syntax error
     * @var string
     */
    protected $_iniSyntaxError;

    /**
     * Directive definition syntax error bis
     * @var string
     */
    protected $_iniSEbis;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_iniFile        = 'files/configs/config.ini';
        $this->_iniFail        = 'files/configs/notexist.ini';
        $this->_iniBadSection  = 'files/configs/badsection.ini';
        $this->_iniBadKey      = 'files/configs/badkey.ini';
        $this->_iniNoParent    = 'files/configs/noparent.ini';
        $this->_iniSyntaxError = 'files/configs/syntaxerror.ini';
        $this->_object         = new SLiib_Config_Ini($this->_iniFile);

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
     * Test get all directives
     *
     * @covers SLiib_Config_Ini::getConfig
     *
     * @return void
     */
    public function testGetConfig()
    {
        $config = $this->_object->getConfig();

        $this->assertObjectHasAttribute('application', $config);
        $this->assertObjectHasAttribute('development', $config);
        $this->assertObjectHasAttribute('production', $config);

        $this->assertInstanceOf('stdClass', $config->application);
        $this->assertInstanceOf('stdClass', $config->development);
        $this->assertInstanceOf('stdClass', $config->production);

        $this->assertObjectHasAttribute('docsMenu', $config->development);
        $this->assertObjectHasAttribute('sysInfos', $config->development);
        $this->assertObjectHasAttribute('docsMenu', $config->production);
        $this->assertObjectHasAttribute('sysInfos', $config->production);

        $this->assertInternalType(
            'string',
            $this->_object->getConfig()->application->sysInfos
        );

        $this->assertEquals('On', $config->development->docsMenu);
        $this->assertEquals('Off', $config->production->docsMenu);

        $this->assertEquals('On', $config->development->sysInfos);
        $this->assertEquals('Off', $config->production->sysInfos);

        $this->assertInstanceOf('stdClass', $config->application->foo);
        $this->assertObjectHasAttribute('bar', $config->application->foo);
        $this->assertInternalType('string', $config->application->foo->bar);
        $this->assertEquals('foobar', $config->application->foo->bar);

        $this->assertInstanceOf('stdClass', $config->application->test->foo->bar);
        $this->assertObjectHasAttribute('z1337', $config->application->test->foo->bar);
        $this->assertObjectHasAttribute('z7331', $config->application->test->foo->bar);
        $this->assertInternalType('string', $config->application->test->foo->bar->z1337);
        $this->assertInternalType('string', $config->application->test->foo->bar->z7331);
        $this->assertEquals('w00t', $config->application->test->foo->bar->z1337);
        $this->assertEquals(':)', $config->application->test->foo->bar->z7331);

    }


    /**
     * Test open inexistant file
     *
     * @return void
     */
    public function testOpenInexistantFile()
    {
        try {
            $config = new SLiib_Config_Ini($this->_iniFail);
        } catch (SLiib_Config_Exception $e) {
            $this->assertInstanceOf('SLiib_Config_Exception', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test Bad section
     *
     * @return void
     */
    public function testBadSection()
    {
        try {
            $config = new SLiib_Config_Ini($this->_iniBadSection);
        } catch (SLiib_Config_Exception_SyntaxError $e) {
            $this->assertInstanceOf('SLiib_Config_Exception_SyntaxError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test Bad key
     *
     * @return void
     */
    public function testBadKey()
    {
        try {
            $config = new SLiib_Config_Ini($this->_iniBadKey);
        } catch (SLiib_Config_Exception_SyntaxError $e) {
            $this->assertInstanceOf('SLiib_Config_Exception_SyntaxError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test no parent
     *
     * @return void
     */
    public function testNoParent()
    {
        try {
            $config = new SLiib_Config_Ini($this->_iniNoParent);
        } catch (SLiib_Config_Exception_SyntaxError $e) {
            $this->assertInstanceOf('SLiib_Config_Exception_SyntaxError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test Syntax error directives
     *
     * @return void
     */
    public function testSyntaxError()
    {
        try {
            $config = new SLiib_Config_Ini($this->_iniSyntaxError);
        } catch (SLiib_Config_Exception_SyntaxError $e) {
            $this->assertInstanceOf('SLiib_Config_Exception_SyntaxError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


}