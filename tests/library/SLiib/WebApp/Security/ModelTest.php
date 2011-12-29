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

namespace SLiib\WebApp\Security;
use SLiib\WebApp\Request;

/**
 * Test class for \SLiib\WebApp\Security\Model.
 * Generated by PHPUnit on 2011-11-17 at 00:19:45.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test object
     *
     * @var \Stubs\Security\Model
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
        Request::init();
        $this->_object = $this->getMockForAbstractClass(
            '\Stubs\Security\Model',
            array('Negative')
        );

        $this->_object->addRule(
            new Rule(
                1,
                'Test Rule',
                '^w00t$',
                array(
                 Model::LOCATION_PARAMETERS,
                 Model::LOCATION_COOKIES,
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
            $this->getMockForAbstractClass('\SLiib\WebApp\Security\Model');
        } catch (Exception $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception', $e);
            return;
        } catch (\Exception $e) {
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
            $stubs = $this->getMockForAbstractClass('\Stubs\Security\Model', array('BadModel'));
        } catch (Exception $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test run and check location
     * Cover all check methods and run method
     *
     * @covers \SLiib\WebApp\Security\Model::run
     * @covers \SLiib\WebApp\Security\Model::_checkParameters
     * @covers \SLiib\WebApp\Security\Model::_checkUserAgent
     * @covers \SLiib\WebApp\Security\Model::_checkMethod
     * @covers \SLiib\WebApp\Security\Model::_checkCookies
     * @covers \SLiib\WebApp\Security\Model::_checkReferer
     *
     * @return void
     */
    public function testRun()
    {
        $rule = new Rule(
            31337,
            'Test run rule',
            'ImALamz-GiveMeYourRoot',
            array(
             Model::LOCATION_COOKIES,
             Model::LOCATION_HTTP_METHOD,
             Model::LOCATION_PARAMETERS,
             Model::LOCATION_REFERER,
             Model::LOCATION_USERAGENT,
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
        } catch (Exception\CheckerError $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\CheckerError', $e);
            return;
        } catch (\Exception $e) {
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
        \Tools\Request::setRequestMethod('POST');
        \Tools\Request::setPost(array('w00t' => ''));
        Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (Exception\HackingAttempt $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\HackingAttempt', $e);
            return;
        } catch (\Exception $e) {
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
        \Tools\Request::setRequestMethod('POST');
        \Tools\Request::setPost(array('param' => 'w00t'));
        Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (Exception\HackingAttempt $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\HackingAttempt', $e);
            return;
        } catch (\Exception $e) {
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
        \Tools\Request::setCookie(array('w00t' => ''));
        Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (Exception\HackingAttempt $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\HackingAttempt', $e);
            return;
        } catch (\Exception $e) {
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
        \Tools\Request::setCookie(array('cookie' => 'w00t'));
        Request::init();

        $this->setUp();

        try {
            $this->_object->run();
        } catch (Exception\HackingAttempt $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\HackingAttempt', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test add rule
     *
     * @covers \SLiib\WebApp\Security\Model::addRule
     * @covers \SLiib\WebApp\Security\Model::getRule
     * @covers \SLiib\WebApp\Security\Exception\CheckerError
     *
     * @return void
     */
    public function testAddRule()
    {
        $this->_object->addRule(
            new Rule(
                2,
                'Test second Rule',
                '^foo(.*)bar$',
                Model::LOCATION_PARAMETERS
            )
        );

        $rule = $this->_object->getRule(2);
        $this->assertInstanceOf('\SLiib\WebApp\Security\Rule', $rule);
        $this->assertEquals(2, $rule->getId());

        try {
            $this->_object->addRule(
                new Rule(
                    1,
                    'Test Rule with already exist id',
                    '^foo(.*)bar$',
                    Model::LOCATION_PARAMETERS
                )
            );
        } catch (Exception\CheckerError $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\CheckerError', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test get rule
     *
     * @covers \SLiib\WebApp\Security\Model::getRule
     * @covers \SLiib\WebApp\Security\Exception\CheckerError
     *
     * @return void
     */
    public function testGetRule()
    {
        $rule = $this->_object->getRule(1);
        $this->assertInstanceOf('\SLiib\WebApp\Security\Rule', $rule);
        $this->assertEquals(1, $rule->getId());

        try {
            $rule = $this->_object->getRule(1337);
        } catch (Exception\CheckerError $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\CheckerError', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


    /**
     * Test get all rules
     *
     * @covers \SLiib\WebApp\Security\Model::getRules
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
     * @covers \SLiib\WebApp\Security\Model::deleteRule
     * @covers \SLiib\WebApp\Security\Model::getRules
     *
     * @return void
     */
    public function testDeleteRule()
    {
        $result = $this->_object->deleteRule(1);
        $this->assertInstanceOf('\SLiib\WebApp\Security\Model', $result);

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
        } catch (Exception\CheckerError $e) {
            $this->assertInstanceOf('\SLiib\WebApp\Security\Exception\CheckerError', $e);
            return;
        } catch (\Exception $e) {
            $this->fail('Bad exception has been raised');
        }

        $this->fail('No exception has been raised');

    }


}
?>
