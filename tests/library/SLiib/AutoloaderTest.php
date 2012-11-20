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
 * Test class for \SLiib\Autoloader.
 * Generated by PHPUnit on 2011-11-22 at 20:10:12.
 *
 * @package    Tests
 * @subpackage UnitTests
 * @uses       SLiib\Autoloader
 */
class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test init autoloader
     *
     * @covers \SLiib\Autoloader::init
     *
     * @return void
     */
    public function testInit()
    {
        $namespace = array(
                      'SLiib' => 'SLiib',
                      APP_NS  => APP_PATH,
                     );

        $section = array(APP_NS => array('Model' => 'models'));
        Autoloader::init($namespace, $section);

        $newSection = array(
                       APP_NS  => array('Controller' => 'controllers'),
                       'Test2' => 'foo',
                      );
        Autoloader::init($namespace, $newSection);
    }

    /**
     * Test autoload
     *
     * @covers \SLiib\Autoloader::autoload
     * @covers \SLiib\Autoloader::searchForInclude
     *
     * @return void
     */
    public function testAutoload()
    {
        $res = Autoloader::autoload('SLiib\Listing');
        $this->assertTrue($res);

        $res = Autoloader::autoload('SLiib\Listing');
        $this->assertTrue($res);

        $res = Autoloader::autoload('SLiib');
        $this->assertFalse($res);

        $res = Autoloader::autoload('SLiip\Listing');
        $this->assertFalse($res);

        $res = Autoloader::autoload('Test\Model\Foo');
        $this->assertFalse($res);

        $res = Autoloader::autoload('Test\Model\MyModel');
        $this->assertTrue($res);

        $res = Autoloader::autoload('\SLiib\NotExist');
        $this->assertFalse($res);
    }
}
