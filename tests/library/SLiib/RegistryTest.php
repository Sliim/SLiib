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
 * Test class for \SLiib\Registry.
 * Generated by PHPUnit on 2011-10-17 at 23:57:42.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class RegistryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * String de test
     * @var string
     */
    private $string = 'This is a string';

    /**
     * Test Registry
     *
     * @covers \SLiib\Registry::set
     * @covers \SLiib\Registry::get
     *
     * @return void
     */
    public function testGetSet()
    {
        Registry::set('myKey', $this->string);
        $this->assertEquals($this->string, Registry::get('myKey'));
    }

    /**
     * Test Registry bis
     *
     * @covers \SLiib\Registry::set
     * @covers \SLiib\Registry::get
     *
     * @return void
     */
    public function testGetSet2()
    {
        $object = new \stdClass;

        $object->attr    = 'foo';
        $object->attrTwo = 'bar';

        Registry::set('myObj', $object);
        $myObj = Registry::get('myObj');

        $this->assertObjectHasAttribute('attr', $myObj);
        $this->assertObjectHasAttribute('attrTwo', $myObj);

        $this->assertEquals('foo', Registry::get('myObj')->attr);
        $this->assertEquals('bar', Registry::get('myObj')->attrTwo);

        $this->assertEquals($this->string, Registry::get('myKey'));
    }

    /**
     * Test set already exists key
     *
     * @return void
     */
    public function testSetAlreadyExistKey()
    {
        $this->setExpectedException('\SLiib\Registry\Exception');
        Registry::set('myKey', $this->string);
    }

    /**
     * Test get not exist key
     *
     * @return void
     */
    public function testGetNotExistKey()
    {
        $this->setExpectedException('\SLiib\Registry\Exception');
        $res = Registry::get('notexist');
    }
}

