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

namespace SLiib;
use SLiib\WebApp,
    SLiib\Autoloader;

/**
 * Test class for \SLiib\WebApp.
 * Generated by PHPUnit on 2011-10-30 at 16:29:08.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class WebAppTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Fake ip
     * @var string
     */
    private $_ip = '127.0.0.1';

    /**
     * Fake method request
     * @var string
     */
    private $_method = 'GET';

    /**
     * Fake user agent
     * @var string
     */
    private $_userAgent = 'Units Test \SLiib\WebApp';

    /**
     * Fake referer
     * @var string
     */
    private $_referer = 'http://dtc.com';

    /**
     * Request instance
     * @var \SLiib\WebApp\Request
     */
    private $_request;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        \Tools\Session::setSession();
        \Tools\Request::setRemoteIp($this->_ip);
        \Tools\Request::setRequestMethod($this->_method);
        \Tools\Request::setUserAgent($this->_userAgent);
        \Tools\Request::setReferer($this->_referer);

    }


    /**
     * Test get instance of \SLiib\WebApp not initialised
     *
     * @return void
     */
    public function testGetInstance()
    {
        try {
            WebApp::getInstance();
        } catch (WebApp_Exception $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Exception', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test no boostrap in app
     *
     * @return void
     */
    public function testNoBootstrap()
    {
        try {
            WebApp::init(
                APP_NS,
                ROOT_PATH
            );
        } catch (WebApp\Exception $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Exception', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run application
     *
     * @return void
     */
    public function testRunApp()
    {
        $this->_runApp();
        $ip      = $this->_request->getClientIp();
        $referer = $this->_request->getReferer();
        $method  = $this->_request->getRequestMethod();
        $ua      = $this->_request->getUserAgent();

        $this->assertEquals($this->_ip, $ip);
        $this->assertEquals($this->_referer, $referer);
        $this->assertEquals($this->_method, $method);
        $this->assertEquals($this->_userAgent, $ua);

    }


    /**
     * Test /test/noview
     *
     * @return void
     */
    public function testRunTestNoview()
    {
        \Tools\Request::setRequestUri('/test/noview');
        $this->_runApp();

    }


    /**
     * Test with params (GET)
     *
     * @return void
     */
    public function testGetParams()
    {
        \Tools\Request::setCookie(array('foo' => 'bar'));
        \Tools\Request::setRequestUri('/test/request/foo/bar/1337/w00t');
        $this->_runApp();

        $params = $this->_request->getParameters();
        $this->assertInternalType('array', $params);
        $this->assertArrayHasKey('foo', $params);
        $this->assertEquals('bar', $params['foo']);
        $this->assertArrayHasKey('1337', $params);
        $this->assertEquals('w00t', $params['1337']);

        $method = $this->_request->getRequestMethod();
        $this->assertEquals('GET', $method);

    }


    /**
     * Test without action
     *
     * @return void
     */
    public function testWithoutAction()
    {
        \Tools\Request::setRequestUri('/index');
        $this->_runApp();

    }


    /**
     * Test bad action
     *
     * @return void
     */
    public function testBadAction()
    {
        \Tools\Request::setRequestUri('/index/notexists');

        try {
            $this->_runApp();
        } catch (WebApp\Exception\NoDispatchable $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Exception\NoDispatchable', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test bad controller
     *
     * @return void
     */
    public function testBadController()
    {
        \Tools\Request::setRequestUri('/notexists');

        try {
            $this->_runApp();
        } catch (WebApp\Exception\NoDispatchable $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Exception\NoDispatchable', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test with post method
     *
     * @return void
     */
    public function testPostMethod()
    {
        \Tools\Request::setRequestMethod('POST');
        \Tools\Request::setPost(
            array(
             'foo'  => 'bar',
             '1337' => 'w00t',
            )
        );

        $this->_runApp();

        $params = $this->_request->getParameters();
        $this->assertInternalType('array', $params);
        $this->assertArrayHasKey('foo', $params);
        $this->assertEquals('bar', $params['foo']);
        $this->assertArrayHasKey('1337', $params);
        $this->assertEquals('w00t', $params['1337']);

        $method = $this->_request->getRequestMethod();
        $this->assertEquals('POST', $method);

    }


    /**
     * Test params with other method
     *
     * @return void
     */
    public function testOtherMethod()
    {
        \Tools\Request::setRequestMethod('1337');
        $this->_runApp();
        $params = $this->_request->getParameters();

        $this->assertTrue(empty($params));

    }


    /**
     * Test specific case of autoloader
     *
     * @return void
     */
    public function testAutoloader()
    {
        $this->assertTrue(Autoloader::autoload('\SLiib\Config'));
        $this->assertTrue(Autoloader::autoload('SLiib_Config'));
        $this->assertFalse(Autoloader::autoload('SLiib'));
        $this->assertFalse(Autoloader::autoload('\Foo\Bar'));
        $this->assertFalse(Autoloader::autoload('\Test\Controller\Unknown'));

    }


    /**
     * Test bad set view in \SLiib\WebApp\View
     *
     * @return void
     */
    public function testBadSetView()
    {
        \Tools\Request::setrequestUri('/test/badsetview');

        try {
            $this->_runApp();
        } catch (WebApp\Exception\InvalidParameter $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Exception\InvalidParameter', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test bad partial in \SLiib\WebApp\View
     *
     * @return void
     */
    public function testBadPartial()
    {
        \Tools\Request::setRequestUri('/test/badpartial');

        try {
            $this->_runApp();
        } catch (WebApp\Exception\InvalidParameter $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Exception\InvalidParameter', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test \SLiib\WebApp\View getter
     *
     * @return void
     */
    public function testViewGetter()
    {
        \Tools\Request::setRequestUri('/test/getterview');

        try {
            $this->_runApp();
        } catch (WebApp\Exception\UndefinedProperty $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Exception\UndefinedProperty', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test Error Handler
     *
     * @return void
     */
    public function testErrorHandler()
    {
        $this->_disablePhpUnitErrorHandler();
        \Tools\Request::setRequestUri('/test/errorhandler');

        try {
            $this->_runApp();
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf('\RuntimeException', $e);
            $this->_enablePhpunitErrorHandler();
            return;
        } catch (\PHPUnit_Framework_Error $e) {
            $this->_enablePhpunitErrorHandler();
            $this->fail('Bad exception has been raised');
        }

        $this->_enablePhpunitErrorHandler();
        $this->fail('No exception has been raised');

    }


    /**
     * Test model action (Controller test)
     *
     * @return void
     */
    public function testModelAction()
    {
        \Tools\Request::setRequestUri('/test/model');
        $this->_runApp();

    }


    /**
     * Test library action (Controller test)
     *
     * @return void
     */
    public function testLibraryAction()
    {
        \Tools\Request::setRequestUri('/test/library');
        $this->_runApp();

    }


    /**
     * Test custom view action (Controller test)
     *
     * @return void
     */
    public function testCustomViewAction()
    {
        \Tools\Request::setRequestUri('/test/customview');
        $this->_runApp();

    }


    /**
     * Test javascript action (Controller test)
     *
     * @return void
     */
    public function testJavascriptAction()
    {
        \Tools\Request::setRequestUri('/test/javascript');
        $this->_runApp();

    }


    /**
     * Test session action (Controller test)
     *
     * @return void
     */
    public function testSessionAction()
    {
        \Tools\Request::setRequestUri('/test/session');
        $this->_runApp();

        \Tools\Request::setRequestMethod('POST');
        \Tools\Request::setPost(
            array(
             'login'    => 'Login',
             'username' => 'Sliim',
             'password' => 'isSecure',
            )
        );
        $this->_runApp();
        $session = new WebApp\Session('TestSession');
        $this->assertTrue(isset($session->logged));
        $this->assertTrue(isset($session->username));
        $this->assertEquals(TRUE, $session->logged);
        $this->assertEquals('Sliim', $session->username);

        \Tools\Request::setPost(array('logout' => 'Logout'));
        $this->_runApp();
        $session = new WebApp\Session('TestSession');
        $this->assertFalse(isset($session->logged));
        $this->assertFalse(isset($session->username));

    }


    /**
     * Test with dash in controller name
     *
     * @return void
     */
    public function testControllerWithDash()
    {
        \Tools\Request::setRequestUri('/my-controller');
        $this->_runApp();

        $controller = $this->_request->getController();
        $this->assertEquals('myController', $controller);

        \Tools\Request::setRequestUri('/my-controller-with-multi-dash');
        $this->_runApp();

        $controller = $this->_request->getController();
        $this->assertEquals('myControllerWithMultiDash', $controller);

    }


    /**
     * Test with dash in action name
     *
     * @return void
     */
    public function testActionWithDash()
    {
        \Tools\Request::setRequestUri('/my-controller/my-action');
        $this->_runApp();

        $action = $this->_request->getAction();
        $this->assertEquals('myAction', $action);

        \Tools\Request::setRequestUri('/my-controller-with-multi-dash/my-action-with-multi-dash');
        $this->_runApp();

        $action = $this->_request->getAction();
        $this->assertEquals('myActionWithMultiDash', $action);

    }


    /**
     * Test with dash in get param name
     *
     * @return void
     */
    public function testParamWithDash()
    {
        \Tools\Request::setRequestUri(
            '/my-controller/my-action/my-param/foo/with-multi-dash-param/bar'
        );
        \Tools\Request::setRequestMethod('GET');
        $this->_runApp();

        $param = $this->_request->getParameters();
        $this->assertEquals(2, count($param));
        $this->assertArrayHasKey('myParam', $param);
        $this->assertArrayHasKey('withMultiDashParam', $param);
        $this->assertEquals('foo', $param['myParam']);
        $this->assertEquals('bar', $param['withMultiDashParam']);

    }


    /**
     * Enable PHPUnit error handler
     *
     * @return void
     */
    private function _enablePhpunitErrorHandler()
    {
        set_error_handler(array('\PHPUnit_Util_ErrorHandler', 'handleError'));

    }


    /**
     * Disable PHPUnit error handler
     *
     * @return void
     */
    private function _disablePhpUnitErrorHandler()
    {
        $bs = new Test_Bootstrap(APP_NS);
        set_error_handler(array($bs, 'errorHandler'));

    }


    /**
     * Run application
     *
     * @return void
     */
    private function _runApp()
    {
        WebApp::init(
            APP_NS,
            APP_PATH
        )->run();

        $this->_request = WebApp\Request::getInstance();

    }


}