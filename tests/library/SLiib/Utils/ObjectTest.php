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

namespace SLiib\Utils;

/**
 * Test class for \SLiib\Utils\Object.
 * Generated by PHPUnit on 2011-12-27 at 23:13:09.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Test merge object
     *
     * @covers \SLiib\Utils\Object::merge
     *
     * @return void
     */
    public function testMerge()
    {
        $foo = new \stdClass();
        $bar = new \stdClass();

        $foo->foo         = 'foo';
        $foo->object      = new \stdClass();
        $foo->object->foo = 'foo';

        $bar->bar         = 'bar';
        $bar->object      = new \stdClass();
        $bar->object->bar = 'bar';

        Object::merge($foo, $bar);

        $this->assertObjectHasAttribute('foo', $foo);
        $this->assertObjectHasAttribute('bar', $foo);
        $this->assertObjectHasAttribute('object', $foo);
        $this->assertInstanceOf('\stdClass', $foo->object);
        $this->assertObjectHasAttribute('foo', $foo->object);
        $this->assertObjectHasAttribute('bar', $foo->object);

    }


}