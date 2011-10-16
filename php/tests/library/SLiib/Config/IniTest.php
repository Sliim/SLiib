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
require_once 'PHPUnit/Framework.php';
require_once 'SLiib/Config/Ini.php';

/**
 * Test class for SLiib_Config_Ini.
 * Generated by PHPUnit on 2011-10-12 at 00:53:28.
 * 
 * @package    SLiib
 * @subpackage UnitTests
 */
class SLiib_Config_IniTest extends PHPUnit_Framework_TestCase
{

  /**
   * @var SLiib_Config_Ini
   */
  protected $_object;

  /**
   * @var string
   */
  protected $_iniFile;


  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   * 
   * @return void
   */
  public function setUp()
  {
    $this->_iniFile = 'files/config.ini';
    $this->_object  = new SLiib_Config_Ini($this->_iniFile);

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
   * @return void
   */
  public function testGetConfig()
  {
    $config = $this->_object->getConfig();

    $this->assertObjectHasAttribute('general', $config);
    $this->assertType('stdClass', $config->general);
    $this->assertObjectHasAttribute('ajaxToolsMenu', $config->general);

    $this->assertType(
        'string',
        $this->_object->getConfig()->general->ajaxToolsMenu
    );

  }


  /**
   * Test rewrite configuration with a other value
   * 
   * @return void
   */
  public function testSetAndSaveConfig()
  {
    $this->_object->setDirective('sysInfos', 'Off', 'general');
    $this->_object->saveConfig();

  }


  /**
   * Test rewrite configuration with an error
   * 
   * @return void
   */
  public function testSetAndSaveConfig2()
  {
    try {
      $this->_object->setDirective('sysInfos', 'On');
    } catch (Exception $e) {
      $this->assertType('SLiib_Config_Exception', $e);
    }

    $this->_object->saveConfig();

  }


}