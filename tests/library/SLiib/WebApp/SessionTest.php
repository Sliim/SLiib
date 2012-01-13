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

namespace SLiib\WebApp;

/**
 * Test class for \SLiib\WebApp\Session.
 * Generated by PHPUnit on 2011-11-30 at 21:26:58.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test object
     * @var \SLiib\WebApp\Session
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return \void
     */
    public function setUp()
    {
        \Tools\Session::setSession();
        Session::init();
        $this->_object = new Session('UnitTest');

        $this->_object->foo = 'bar';

    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return \void
     */
    public function tearDown()
    {
        Session::destroy();

    }


    /**
     * Test constructor
     *
     * @return \void
     */
    public function testConstruct()
    {
        Session::init();
        $session = new Session('TestConstruct');
        $this->assertInstanceOf('\SLiib\WebApp\Session', $session);

        $session->foo = 'bar';

        $sessionBis = new Session('TestConstruct');
        $this->assertInstanceOf('\SLiib\WebApp\Session', $sessionBis);
        $this->assertTrue(isset($sessionBis->foo));
        $this->assertEquals('bar', $sessionBis->foo);

        Session::destroy();

        try {
            $sessionBibis = new Session('TestConstruct');
        } catch (Session\Exception $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Session\Exception', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test get property
     *
     * @covers \SLiib\WebApp\Session::__get
     *
     * @return \void
     */
    public function testGet()
    {
        $session = new Session('UnitTest');
        $this->assertEquals('bar', $session->foo);

        try {
            $property = $session->property;
        } catch (Session\Exception $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Session\Exception', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test set property
     *
     * @covers \SLiib\WebApp\Session::__set
     *
     * @return \void
     */
    public function testSet()
    {
        $this->_object->myProperty = 'w00tw00t';
        $this->assertEquals('w00tw00t', $this->_object->myProperty);

    }


    /**
     * Test unset property
     *
     * @covers \SLiib\WebApp\Session::__unset
     *
     * @return \void
     */
    public function testUnset()
    {
        unset($this->_object->foo);
        $this->assertFalse(isset($this->_object->foo));

        try {
            unset($this->_object->notexist);
        } catch (Session\Exception $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Session\Exception', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test isset property
     *
     * @covers \SLiib\WebApp\Session::__isset
     *
     * @return \void
     */
    public function testIsset()
    {
        $this->assertTrue(isset($this->_object->foo));
        $this->assertFalse(isset($this->_object->f00));

    }


    /**
     * Test clear
     *
     * @covers \SLiib\WebApp\Session::clear
     *
     * @return \void
     */
    public function testClear()
    {
        $this->_object->clear();
        $this->assertFalse(isset($this->_object->foo));

    }


    /**
     * Test namespace exist
     *
     * @covers \SLiib\WebApp\Session::namespaceExist
     *
     * @return \void
     */
    public function testNamespaceExist()
    {
        $this->assertTrue(Session::namespaceExist('UnitTest'));
        $this->assertFalse(Session::namespaceExist('MyNamespace'));

    }


    /**
     * Test session is started
     *
     * @covers \SLiib\WebApp\Session::started
     *
     * @return \void
     */
    public function testStarted()
    {
        $this->assertTrue(Session::started());

    }


}
?>
