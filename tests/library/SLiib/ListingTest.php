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
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * Test class for \SLiib\Listing.
 * Generated by PHPUnit on 2011-10-14 at 21:52:17.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class ListingTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Objet de test
     * @var \SLiib\Listing
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @covers \SLiib\Listing::__construct
     * @covers \SLiib\Listing::_list
     *
     * @return void
     */
    public function setUp()
    {
        $this->_object = new Listing('./', 'tests', array('.', '..'));

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
     * @covers \SLiib\Listing::getList
     *
     * @return void
     */
    public function testGetList()
    {
        $list = $this->_object->getList();

        $this->assertInternalType('array', $list);

    }


    /**
     * Test rangement par ordre croissant
     *
     * @covers \SLiib\Listing::sort
     *
     * @return void
     */
    public function testSort()
    {
        $list = $this->_object->getList();
        natcasesort($list);
        $this->_object->sort();
        $listbis = $this->_object->getList();

        $this->assertEquals(array_merge($list), $listbis);

    }


    /**
     * Test rangement par ordre croissant inversé
     *
     * @covers \SLiib\Listing::usort
     *
     * @return void
     */
    public function testUsort()
    {
        $this->_object->sort();
        $list = $this->_object->getList();

        $list = array_reverse($list);
        $this->_object->usort();

        $this->assertEquals($list, $this->_object->getList());

    }


    /**
     * Test create object with inexisting directory
     *
     * @covers \SLiib\Listing\Exception
     *
     * @return void
     */
    public function testInexistDir()
    {
        try {
            $list = new Listing('/tmp/notexists', 'fake', array());
        } catch (Listing\Exception $e) {
            $this->assertInstanceOf('\SLiib\Listing\Exception', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


}