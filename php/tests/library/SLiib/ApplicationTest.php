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
require_once 'SLiib/Application.php';

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../others/Application/'));
define('APP_PATH', realpath(ROOT_PATH . '/application/'));
define('APP_NS', 'Test');

/**
 * Test class for SLiib_Application.
 * Generated by PHPUnit on 2011-10-30 at 16:29:08.
 *
 * @package    SLiib
 * @subpackage UnitTests
 */
class SLiib_ApplicationTest extends PHPUnit_Framework_TestCase
{

    const ENABLE_OUTPUT_CLEANER = TRUE;

    /**
     * @var string
     */
    private $_ip = '127.0.0.1';

    /**
     * @var string
     */
    private $_method = 'GET';

    /**
     * @var string
     */
    private $_userAgent = 'Units Test SLiib_Application';

    /**
     * @var string
     */
    private $_referer = 'http://dtc.com';

    /**
     * @var SLiib_HTTP_Request
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
        $this->_setServerInfo('REMOTE_ADDR', $this->_ip);
        $this->_setServerInfo('REQUEST_METHOD', $this->_method);
        $this->_setServerInfo('HTTP_USER_AGENT', $this->_userAgent);
        $this->_setServerInfo('HTTP_REFERER', $this->_referer);

    }


    /**
     * Test get instance of SLiib_Application not initialised
     *
     * @return void
     */
    public function testGetInstance()
    {
        try {
            SLiib_Application::getInstance();
        } catch (SLiib_Application_Exception $e) {
            $this->assertType('SLiib_Application_Exception', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test getInstance SLiib_HTTP_Request not initialized
     *
     * @return void
     */
    public function testGetInstanceRequest()
    {
        try {
            SLiib_HTTP_Request::getInstance();
        } catch (SLiib_HTTP_Request_Exception $e) {
            $this->assertType('SLiib_HTTP_Request_Exception', $e);
            return;
        } catch (Exception $e) {
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
            SLiib_Application::init(
                APP_NS,
                ROOT_PATH
            );
        } catch (SLiib_Application_Exception $e) {
            $this->assertType('SLiib_Application_Exception', $e);
            return;
        } catch (Exception $e) {
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
        $this->_setServerInfo('REQUEST_URI', '/test/noview');
        $this->_runApp();

    }


    /**
     * Test with params (GET)
     *
     * @return void
     */
    public function testGetParams()
    {
        $this->_setCookie(array('foo' => 'bar'));
        $this->_setServerInfo('REQUEST_URI', '/test/request/foo/bar/1337/w00t');
        $this->_runApp();

        $params = $this->_request->getParameters();
        $this->assertType('array', $params);
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
        $this->_setServerInfo('REQUEST_URI', '/index');
        $this->_runApp();

    }


    /**
     * Test bad action
     *
     * @return void
     */
    public function testBadAction()
    {
        $this->_setServerInfo('REQUEST_URI', '/index/notexists');

        try {
            $this->_runApp();
        } catch (SLiib_Application_Controller_Exception_BadMethodCall $e) {
            $this->assertType('SLiib_Application_Controller_Exception_BadMethodCall', $e);
            return;
        } catch (Exception $e) {
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
        $this->_setServerInfo('REQUEST_URI', '/notexists');

        try {
            $this->_runApp();
        } catch (SLiib_Application_Controller_Exception $e) {
            $this->assertType('SLiib_Application_Controller_Exception', $e);
            return;
        } catch (Exception $e) {
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
        $this->_setServerInfo('REQUEST_METHOD', 'POST');
        $this->_setPost(
            array(
             'foo'  => 'bar',
             '1337' => 'w00t',
            )
        );

        $this->_runApp();

        $params = $this->_request->getParameters();
        $this->assertType('array', $params);
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
        $this->_setServerInfo('REQUEST_METHOD', '1337');
        $this->_runApp();

        $params = $this->_request->getParameters();

        $this->assertNull($params);

    }


    /**
     * Test specific case of autoloader
     *
     * @return void
     */
    public function testAutoloader()
    {
        $this->assertTrue(SLiib_Autoloader::autoload('SLiib_Config'));
        $this->assertFalse(SLiib_Autoloader::autoload('SLiib'));
        $this->assertFalse(SLiib_Autoloader::autoload('Foo_Bar'));
        $this->assertFalse(SLiib_Autoloader::autoload('Test_Controller_Unknown'));

    }


    /**
     * Test bad set view in SLiib_Application_View
     *
     * @return void
     */
    public function testBadSetView()
    {
        $this->_setServerInfo('REQUEST_URI', '/test/badsetview');

        try {
            $this->_runApp();
        } catch (SLiib_Application_View_Exception_InvalidParam $e) {
            $this->assertType('SLiib_Application_View_Exception_InvalidParam', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test bad partial in SLiib_Application_View
     *
     * @return void
     */
    public function testBadPartial()
    {
        $this->_setServerInfo('REQUEST_URI', '/test/badpartial');

        try {
            $this->_runApp();
        } catch (SLiib_Application_View_Exception_InvalidParam $e) {
            $this->assertType('SLiib_Application_View_Exception_InvalidParam', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test SLiib_Application_View getter
     *
     * @return void
     */
    public function testViewGetter()
    {
        $this->_setServerInfo('REQUEST_URI', '/test/getterview');

        try {
            $this->_runApp();
        } catch (SLiib_Application_View_Exception_InvalidParam $e) {
            $this->assertType('SLiib_Application_View_Exception_InvalidParam', $e);
            return;
        } catch (Exception $e) {
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
        //Fix PHPUnit convertErrorToException
        $bs = new Test_Bootstrap(APP_NS);
        set_error_handler(array($bs, 'errorHandler'));

        $this->_setServerInfo('REQUEST_URI', '/test/errorhandler');

        try {
            $this->_runApp();
        } catch (RuntimeException $e) {
            $this->assertType('RuntimeException', $e);

            set_error_handler(array('PHPUnit_Util_ErrorHandler', 'handleError'));
            return;
        }  catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');
        set_error_handler(array('PHPUnit_Util_ErrorHandler', 'handleError'));

    }


    /**
     * Test model action (Controller test)
     *
     * @return void
     */
    public function testModelAction()
    {
        $this->_setServerInfo('REQUEST_URI', '/test/model');
        $this->_runApp();

    }


    /**
     * Test library action (Controller test)
     *
     * @return void
     */
    public function testLibraryAction()
    {
        $this->_setServerInfo('REQUEST_URI', '/test/library');
        $this->_runApp();

    }


    /**
     * Test custom view action (Controller test)
     *
     * @return void
     */
    public function testCustomViewAction()
    {
        $this->_setServerInfo('REQUEST_URI', '/test/customview');
        $this->_runApp();

    }


    /**
     * Test javascript action (Controller test)
     *
     * @return void
     */
    public function testJavascriptAction()
    {
        $this->_setServerInfo('REQUEST_URI', '/test/javascript');
        $this->_runApp();

    }


    /**
     * Run application
     *
     * @return void
     */
    private function _runApp()
    {
        if (self::ENABLE_OUTPUT_CLEANER) {
            ob_start();
        }

        SLiib_Application::init(
            APP_NS,
            APP_PATH
        )->run();

        if (self::ENABLE_OUTPUT_CLEANER) {
            ob_clean();
            ob_flush();
        }

        $this->_request = SLiib_HTTP_Request::getInstance();

    }


    /**
     * Set index of $_SERVER for tests
     *
     * @param string $index Index of $_SERVER
     * @param string $value Value to assign
     *
     * @return void
     */
    private function _setServerInfo($index, $value)
    {
        $GLOBALS['_SERVER'];
        $_SERVER[$index] = $value;

    }


    /**
     * Simulate a $_POST
     *
     * @param array $post $_POST you want
     *
     * @return void
     */
    private function _setPost(array $post)
    {
        $GLOBALS['_POST'];
        $_POST = $post;

    }


    /**
     * Simulate a $_COOKIE
     *
     * @param array $cookies $_COOKIE you want
     *
     * @return void
     */
    private function _setCookie(array $cookies)
    {
        $GLOBALS['_COOKIE'];
        $_COOKIE = $cookies;

    }


}