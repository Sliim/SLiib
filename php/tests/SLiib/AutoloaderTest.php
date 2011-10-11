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
 * @category SLiib
 * @package  SLiib_AutoloaderTest
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.1
 * @link     http://www.sliim-projects.eu
 */

require_once 'PHPUnit/Framework.php';
require_once '/home/sliim/projects/SLiib/php/library/SLiib/Autoloader.php';

/**
 * Test class for SLiib_Autoloader.
 * Generated by PHPUnit on 2011-10-11 at 00:55:37.
 * 
 * @package SLiib_AutoloaderTest
 */
class SLiib_AutoloaderTest extends PHPUnit_Framework_TestCase
{


  /**
   * Autoloader initialisation
   * 
   * @return void
   */
  public function init()
  {
    SLiib_Autoloader::init(array('SLiib'));

  }


  /**
   * Test double init. Must be throw a SLiib_Autoloader_Exception
   * 
   * @return void
   */
  public function testInit()
  {
    try {
      SLiib_Autoloader::init(array('SLiib'));
    } catch (SLiib_Autoloader_Exception $e) {
      echo 'SLiib_Autoloader_Exception catched !' . PHP_EOL;
    }

  }


  /**
   * Test autoload
   * 
   * @return void
   */
  public function testAutoload()
  {
    $obj = new SLiib_Listing(
        './',
        'SLiib Test Suite',
        array(
         '.',
         '..',
        )
    );

    $this->assertType('SLiib_Listing', $obj);

  }


}