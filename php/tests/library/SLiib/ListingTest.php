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
 * @version    Release: 0.1
 * @link       http://www.sliim-projects.eu
 */
require_once 'PHPUnit/Framework.php';
require_once 'SLiib/Listing.php';

/**
 * Test class for SLiib_Listing.
 * Generated by PHPUnit on 2011-10-14 at 21:52:17.
 * 
 * @package    SLiib
 * @subpackage UnitTests
 */
class SLiib_ListingTest extends PHPUnit_Framework_TestCase
{

  /**
   * @var SLiib_Listing
   */
  protected $_object;


  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   * 
   * @return void
   */
  public function setUp()
  {
    $this->_object = new SLiib_Listing('./', 'tests', array('.', '..'));

  }


  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   * 
   * @return void
   */
  public function tearDown()
  {
    unset($this->_objet);

  }


  /**
   * Test get list
   * 
   * @return void
   */
  public function testGetList()
  {
    $list = $this->_object->getList();

    $this->assertType('array', $list);

  }


  /**
   * Test rangement par ordre croissant
   * 
   * @return void
   */
  public function testSort()
  {
    $list = $this->_object->getList();
    natcasesort($list);
    $this->_object->sort();
    $listbis = $this->_object->getList();

    $this->assertEquals($list, $listbis);

  }


}
?>
