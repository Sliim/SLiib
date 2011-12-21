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
 * Test class for SLiib_WebApp_Request.
 * Generated by PHPUnit on 2011-11-22 at 20:07:43.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SLiib_WebApp_RequestTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test object
     * @var SLiib_WebApp_Request
     */
    protected $_object;

    /**
     * Fake request uri
     * @var string
     */
    private $_requestUri = '/foo/bar/getparam/value';

    /**
     * Fake client ip
     * @var string
     */
    private $_clientIp = '127.0.0.3';

    /**
     * Fake user agent
     * @var string
     */
    private $_ua = '31337bot';

    /**
     * Fake http method
     * @var string
     */
    private $_method = 'GET';

    /**
     * Fake referer
     * @var string
     */
    private $_referer = 'http://www.hellokitty.com';

    /**
     * Post params
     * @var array
     */
    private $_post = array();

    /**
     * Cookies
     * @var array
     */
    private $_cookies = array();


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_post    = array('params' => 'baz');
        $this->_cookies = array('user_password' => 'passSecur3d');

        Static_Request::setRequestUri($this->_requestUri);
        Static_Request::setRemoteIp($this->_clientIp);
        Static_Request::setUserAgent($this->_ua);
        Static_Request::setRequestMethod($this->_method);
        Static_Request::setReferer($this->_referer);
        Static_Request::setPost($this->_post);
        Static_Request::setCookie($this->_cookies);

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
     * Test get an instance not initialized
     *
     * @return void
     */
    public function testGetInstanceNotInit()
    {
        try {
            $object = SLiib_WebApp_Request::getInstance();
        } catch (SLiib_WebApp_Request_Exception $e) {
            $this->assertInstanceOf('SLiib_WebApp_Request_Exception', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test get instance
     *
     * @covers SLiib_WebApp_Request::init
     * @covers SLiib_WebApp_Request::__construct
     * @covers SLiib_WebApp_Request::getInstance
     * @covers SLiib_WebApp_Request::_initProperties
     * @covers SLiib_WebApp_Request::_parseUrl
     *
     * @return void
     */
    public function testGetInstance()
    {
        SLiib_WebApp_Request::init();
        $object = SLiib_WebApp_Request::getInstance();
        $this->assertInstanceOf('SLiib_WebApp_Request', $object);

    }


    /**
     * Test get controller
     *
     * @covers SLiib_WebApp_Request::getController
     *
     * @return void
     */
    public function testGetController()
    {
        $this->_setObject();
        $this->assertEquals('foo', $this->_object->getController());

    }


    /**
     * Test index controller & index action
     *
     * @return void
     */
    public function testIndexControllerIndexAction()
    {
        Static_Request::setRequestUri('/');
        $this->_setObject();
        $this->assertEquals('index', $this->_object->getController());
        $this->assertEquals('index', $this->_object->getAction());

    }


    /**
     * Test index action
     *
     * @return void
     */
    public function testIndexAction()
    {
        Static_Request::setRequestUri('/foo');
        $this->_setObject();
        $this->assertEquals('foo', $this->_object->getController());
        $this->assertEquals('index', $this->_object->getAction());

    }


    /**
     * Test get action
     *
     * @covers SLiib_WebApp_Request::getAction
     *
     * @return void
     */
    public function testGetAction()
    {
        $this->_setObject();
        $this->assertEquals('bar', $this->_object->getAction());

    }


    /**
     * Test get request uri
     *
     * @covers SLiib_WebApp_Request::getRequestUri
     *
     * @return void
     */
    public function testGetRequestUri()
    {
        $this->_setObject();
        $this->assertEquals($this->_requestUri, $this->_object->getRequestUri());

    }


    /**
     * Test get parameters post | get
     *
     * @covers SLiib_WebApp_Request::getParameters
     *
     * @return void
     */
    public function testGetParameters()
    {
        $this->_setObject();
        $params = $this->_object->getParameters();

        $this->assertArrayHasKey('getparam', $params);
        $this->assertEquals('value', $params['getparam']);

        Static_Request::setRequestMethod('POST');
        $this->_setObject();

        $params = $this->_object->getParameters();
        $this->assertEquals($this->_post, $params);

        Static_Request::setRequestMethod('WOOT');
        $this->_setObject();

        $params = $this->_object->getParameters();
        $this->assertTrue(empty($params));

    }


    /**
     * Test get client ip
     *
     * @covers SLiib_WebApp_Request::getClientIp
     *
     * @return void
     */
    public function testGetClientIp()
    {
        $this->_setObject();
        $this->assertEquals($this->_clientIp, $this->_object->getClientIp());

    }


    /**
     * Test get user agent
     *
     * @covers SLiib_WebApp_Request::getUserAgent
     *
     * @return void
     */
    public function testGetUserAgent()
    {
        $this->_setObject();
        $this->assertEquals($this->_ua, $this->_object->getUserAgent());

    }


    /**
     * Test get request method
     *
     * @covers SLiib_WebApp_Request::getRequestMethod
     *
     * @return void
     */
    public function testGetRequestMethod()
    {
        $this->_setObject();
        $this->assertEquals($this->_method, $this->_object->getRequestMethod());

    }


    /**
     * Test get cookies
     *
     * @covers SLiib_WebApp_Request::getCookies
     *
     * @return void
     */
    public function testGetCookies()
    {
        $this->_setObject();
        $this->assertEquals($this->_cookies, $this->_object->getCookies());

    }


    /**
     * Test get referer
     *
     * @covers SLiib_WebApp_Request::getReferer
     *
     * @return void
     */
    public function testGetReferer()
    {
        $this->_setObject();
        $this->assertEquals($this->_referer, $this->_object->getReferer());

    }


    /**
     * Test if request is a post method
     *
     * @covers SLiib_WebApp_Request::isPost
     *
     * @return void
     */
    public function testIsPost()
    {
        Static_Request::setRequestMethod('POST');
        $this->_setObject();

        $this->assertTrue($this->_object->isPost());

        Static_Request::setRequestMethod('GET');
        $this->_setObject();

        $this->assertFalse($this->_object->isPost());

    }


    /**
     * Set test object
     *
     * @return void
     */
    private function _setObject()
    {
        SLiib_WebApp_Request::init();
        $this->_object = SLiib_WebApp_Request::getInstance();

    }


}
?>
