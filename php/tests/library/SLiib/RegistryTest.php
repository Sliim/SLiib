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
require_once 'SLiib/Registry.php';

/**
 * Test class for SLiib_Registry.
 * Generated by PHPUnit on 2011-10-17 at 23:57:42.
 *
 * @package    SLiib
 * @subpackage UnitTests
 */
class SLiib_RegistryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var string String de test
     */
    private $_string = 'This is a string';


    /**
     * Test Registry
     *
     * @return void
     */
    public function testGetSet()
    {
        SLiib_Registry::set('myKey', $this->_string);
        $this->assertEquals($this->_string, SLiib_Registry::get('myKey'));

    }


    /**
     * Test Registry bis
     *
     * @return void
     */
    public function testGetSet2()
    {
        $object = new stdClass;

        $object->attr    = 'foo';
        $object->attrTwo = 'bar';

        SLiib_Registry::set('myObj', $object);
        $myObj = SLiib_Registry::get('myObj');

        $this->assertObjectHasAttribute('attr', $myObj);
        $this->assertObjectHasAttribute('attrTwo', $myObj);

        $this->assertEquals('foo', SLiib_Registry::get('myObj')->attr);
        $this->assertEquals('bar', SLiib_Registry::get('myObj')->attrTwo);

        $this->assertEquals($this->_string, SLiib_Registry::get('myKey'));

    }


    /**
     * Test set already exists key
     *
     * @return void
     */
    public function testSetAlreadyExistKey()
    {
        try {
            SLiib_Registry::set('myKey', $this->_string);
        } catch (SLiib_Registry_Exception $e) {
            $this->assertType('SLiib_Registry_Exception', $e);
            return;
        }

        $this->fail('Bad exception thrown');

    }


    /**
     * Test get not exist key
     *
     * @return void
     */
    public function testGetNotExistKey()
    {
        try {
            $res = SLiib_Registry::get('notexist');
        } catch (SLiib_Registry_Exception $e) {
            $this->assertType('SLiib_Registry_Exception', $e);
            return;
        }

        $this->fail('Bad exception thrown');

    }


}