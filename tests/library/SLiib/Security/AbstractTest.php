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
 * Test class for SLiib_Security_Abstract.
 * Generated by PHPUnit on 2011-11-17 at 00:19:45.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SLiib_Security_AbstractTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test object
     *
     * @var Stubs_Security_Abstract
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
        SLiib_HTTP_Request::init();
        $this->_object = $this->getMockForAbstractClass(
            'Stubs_Security_Abstract',
            array('Negative')
        );

        $this->_object->addRule(
            new SLiib_Security_Rule(
                1,
                'Test Rule',
                '^w00t$',
                array(
                 SLiib_Security_Abstract::LOCATION_PARAMETERS,
                 SLiib_Security_Abstract::LOCATION_COOKIES,
                )
            )
        );

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
     * Test no security model
     *
     * @return void
     */
    public function testNoSecurityModel()
    {
        try {
            $this->getMockForAbstractClass('SLiib_Security_Abstract');
        } catch (SLiib_Security_Exception $e) {
            $this->assertInstanceOf('SLiib_Security_Exception', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test bad security model
     *
     * @return void
     */
    public function testBadSecurityModel()
    {
        try {
            $stubs = $this->getMockForAbstractClass('Stubs_Security_Abstract', array('BadModel'));
        } catch (SLiib_Security_Exception $e) {
            $this->assertInstanceOf('SLiib_Security_Exception', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run
     *
     * @covers SLiib_Security_Abstract::run
     * @covers SLiib_Security_Abstract::_checkParameters
     * @covers SLiib_Security_Abstract::_checkUserAgent
     * @covers SLiib_Security_Abstract::_checkMethod
     * @covers SLiib_Security_Abstract::_checkCookies
     * @covers SLiib_Security_Abstract::_checkReferer
     *
     * @return void
     */
    public function testRun()
    {
        $rule = new SLiib_Security_Rule(
            31337,
            'Test run rule',
            'ImALamz-GiveMeYourRoot',
            array(
             SLiib_Security_Abstract::LOCATION_COOKIES,
             SLiib_Security_Abstract::LOCATION_HTTP_METHOD,
             SLiib_Security_Abstract::LOCATION_PARAMETERS,
             SLiib_Security_Abstract::LOCATION_REFERER,
             SLiib_Security_Abstract::LOCATION_USERAGENT,
            )
        );

        $this->_object->addRule($rule)->run();

    }


    /**
     * Test run with bad location
     *
     * @return void
     */
    public function testRunBadLocation()
    {
        $this->_object->getRule(1)->addLocation('BadLoacation');

        try {
            $this->_object->run();
        } catch (SLiib_Security_Exception_CheckerError $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_CheckerError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run with hacking attempt in parameter key
     *
     * @return void
     */
    public function testRunWithHackingAttemptParamKey()
    {
        Static_Request::setServerInfo('REQUEST_METHOD', 'POST');
        Static_Request::setPost(array('w00t' => ''));
        SLiib_HTTP_Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (SLiib_Security_Exception_HackingAttempt $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_HackingAttempt', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run with hacking attempt in parameter Value
     *
     * @return void
     */
    public function testRunWithHackingAttemptParamVal()
    {
        Static_Request::setServerInfo('REQUEST_METHOD', 'POST');
        Static_Request::setPost(array('param' => 'w00t'));
        SLiib_HTTP_Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (SLiib_Security_Exception_HackingAttempt $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_HackingAttempt', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run with hacking attempt in cookie key
     *
     * @return void
     */
    public function testRunWithHackingAttemptCookieKey()
    {
        Static_Request::setCookie(array('w00t' => ''));
        SLiib_HTTP_Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (SLiib_Security_Exception_HackingAttempt $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_HackingAttempt', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run with hacking attempt in cookie value
     *
     * @return void
     */
    public function testRunWithHackingAttemptCookieVal()
    {
        Static_Request::setCookie(array('cookie' => 'w00t'));
        SLiib_HTTP_Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (SLiib_Security_Exception_HackingAttempt $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_HackingAttempt', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test add rule
     *
     * @covers SLiib_Security_Abstract::addRule
     * @covers SLiib_Security_Abstract::getRule
     * @covers SLiib_Security_Exception_CheckerError
     *
     * @return void
     */
    public function testAddRule()
    {
        $this->_object->addRule(
            new SLiib_Security_Rule(
                2,
                'Test second Rule',
                '^foo(.*)bar$',
                SLiib_Security_Abstract::LOCATION_PARAMETERS
            )
        );

        $rule = $this->_object->getRule(2);
        $this->assertInstanceOf('SLiib_Security_Rule', $rule);
        $this->assertEquals(2, $rule->getId());

        try {
            $this->_object->addRule(
                new SLiib_Security_Rule(
                    1,
                    'Test Rule with already exist id',
                    '^foo(.*)bar$',
                    SLiib_Security_Abstract::LOCATION_PARAMETERS
                )
            );
        } catch (SLiib_Security_Exception_CheckerError $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_CheckerError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test get rule
     *
     * @covers SLiib_Security_Abstract::getRule
     * @covers SLiib_Security_Exception_CheckerError
     *
     * @return void
     */
    public function testGetRule()
    {
        $rule = $this->_object->getRule(1);
        $this->assertInstanceOf('SLiib_Security_Rule', $rule);
        $this->assertEquals(1, $rule->getId());

        try {
            $rule = $this->_object->getRule(1337);
        } catch (SLiib_Security_Exception_CheckerError $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_CheckerError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test get all rules
     *
     * @covers SLiib_Security_Abstract::getRules
     *
     * @return void
     */
    public function testGetRules()
    {
        $rules = $this->_object->getRules();
        $this->assertInternalType('array', $rules);
        $this->assertArrayHasKey(1, $rules);
        $this->assertEquals(1, count($rules));

    }


    /**
     * Test delete rule
     *
     * @covers SLiib_Security_Abstract::deleteRule
     * @covers SLiib_Security_Abstract::getRules
     *
     * @return void
     */
    public function testDeleteRule()
    {
        $result = $this->_object->deleteRule(1);
        $this->assertInstanceOf('SLiib_Security_Abstract', $result);

        $rules = $this->_object->getRules();
        $this->assertInternalType('array', $rules);
        $this->assertEquals(0, count($rules));

    }


    /**
     * Test delete rule not exists
     *
     * @return void
     */
    public function testDeleteRuleNotExists()
    {
        try {
            $rule = $this->_object->deleteRule(1337);
        } catch (SLiib_Security_Exception_CheckerError $e) {
            $this->assertInstanceOf('SLiib_Security_Exception_CheckerError', $e);
            return;
        } catch (Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


}
?>
