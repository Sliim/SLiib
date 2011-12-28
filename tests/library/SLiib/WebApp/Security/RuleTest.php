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
 * Test class for SLiib_WebApp_Security_Rule.
 * Generated by PHPUnit on 2011-11-16 at 22:38:01.
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SLiib_WebApp_Security_RuleTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test object
     * @var SLiib_WebApp_Security_Rule
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @covers SLiib_WebApp_Security_Rule::__construct
     *
     * @return void
     */
    public function setUp()
    {
        $this->_object = new SLiib_WebApp_Security_Rule(
            1337,
            'RuleTest',
            '^foo(.*)bar$',
            SLiib_WebApp_Security_Model::LOCATION_PARAMETERS
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
     * Test get id
     *
     * @covers SLiib_WebApp_Security_Rule::getId
     *
     * @return void
     */
    public function testGetId()
    {
        $id = $this->_object->getId();
        $this->assertInternalType('int', $id);
        $this->assertEquals(1337, $id);

    }


    /**
     * Test get message
     *
     * @covers SLiib_WebApp_Security_Rule::getMessage
     *
     * @return void
     */
    public function testGetMessage()
    {
        $msg = $this->_object->getMessage();
        $this->assertInternalType('string', $msg);
        $this->assertEquals('RuleTest', $msg);

    }


    /**
     * Test get pattern
     *
     * @covers SLiib_WebApp_Security_Rule::getPattern
     *
     * @return void
     */
    public function testGetPattern()
    {
        $pattern = $this->_object->getPattern();
        $this->assertInternalType('string', $pattern);
        $this->assertEquals('^foo(.*)bar$', $pattern);

    }


    /**
     * Test get Location
     *
     * @covers SLiib_WebApp_Security_Rule::getLocation
     *
     * @return void
     */
    public function testGetLocation()
    {
        $location = $this->_object->getLocation();
        $this->assertInternalType('array', $location);
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_PARAMETERS, $location));

    }


    /**
     * Test set pattern
     *
     * @covers SLiib_WebApp_Security_Rule::setPattern
     *
     * @return void
     */
    public function testSetPattern()
    {
        $this->_object->setPattern('hacked');
        $pattern = $this->_object->getPattern();

        $this->assertInternalType('string', $pattern);
        $this->assertEquals('hacked', $pattern);

    }


    /**
     * Test add location
     *
     * @covers SLiib_WebApp_Security_Rule::addLocation
     *
     * @return void
     */
    public function testAddLocation()
    {
        $this->_object->addLocation(SLiib_WebApp_Security_Model::LOCATION_COOKIES);
        $location = $this->_object->getLocation();

        $this->assertInternalType('array', $location);
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_PARAMETERS, $location));
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_COOKIES, $location));

        $this->_object->addLocation(
            array(
             SLiib_WebApp_Security_Model::LOCATION_HTTP_METHOD,
             SLiib_WebApp_Security_Model::LOCATION_REFERER,
             SLiib_WebApp_Security_Model::LOCATION_USERAGENT,
            )
        );

        $location = $this->_object->getLocation();
        $this->assertInternalType('array', $location);
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_PARAMETERS, $location));
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_COOKIES, $location));
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_HTTP_METHOD, $location));
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_REFERER, $location));
        $this->assertTrue(in_array(SLiib_WebApp_Security_Model::LOCATION_USERAGENT, $location));

    }


    /**
     * Test Delete location
     *
     * @covers SLiib_WebApp_Security_Rule::deleteLocation
     *
     * @return void
     */
    public function testDeleteLocation()
    {
        $this->_object->deleteLocation(SLiib_WebApp_Security_Model::LOCATION_PARAMETERS);
        $location = $this->_object->getLocation();

        $this->assertInternalType('array', $location);
        $this->assertTrue(empty($location));

    }


    /**
     * Test add pattern element
     *
     * @covers SLiib_WebApp_Security_Rule::addPatternElement
     * @covers SLiib_WebApp_Security_Rule::_reloadPattern
     *
     * @return void
     */
    public function testAddPatternElement()
    {
        $this->_object
            ->addPatternElement('hacked')
            ->addPatternElement(array('foo', 'bar'));

        $pattern = $this->_object->getPattern();

        $this->assertInternalType('string', $pattern);
        $this->assertEquals('(hacked|foo|bar)', $pattern);

    }


    /**
     * Test delete pattern element
     *
     * @covers SLiib_WebApp_Security_Rule::deletePatternElement
     * @covers SLiib_WebApp_Security_Rule::_reloadPattern
     *
     * @return void
     */
    public function testDeletePatternElement()
    {
        $this->_object
            ->addPatternElement(array('foo', 'bar'))
            ->deletePatternElement('foo');

        $pattern = $this->_object->getPattern();

        $this->assertInternalType('string', $pattern);
        $this->assertEquals('(bar)', $pattern);

    }


    /**
     * Test enable preg quote
     *
     * @covers SLiib_WebApp_Security_Rule::enablePregQuote
     * @covers SLiib_WebApp_Security_Rule::setPattern
     * @covers SLiib_WebApp_Security_Rule::addPatternElement
     *
     * @return void
     */
    public function testEnablePregQuote()
    {
        $this->_object->enablePregQuote()
            ->setPattern('foo/bar');
        $this->assertEquals('foo\/bar', $this->_object->getPattern());

        $this->_object->disablePregQuote()
            ->enablePregQuote()
            ->addPatternElement(
                array(
                 'foo/bar',
                 'w00t..w00t',
                )
            );
        $this->assertEquals('(foo\/bar|w00t\.\.w00t)', $this->_object->getPattern());

    }


    /**
     * Test enable preg quote
     *
     * @covers SLiib_WebApp_Security_Rule::disablePregQuote
     * @covers SLiib_WebApp_Security_Rule::setPattern
     * @covers SLiib_WebApp_Security_Rule::addPatternElement
     *
     * @return void
     */
    public function testDisablePregQuote()
    {
        $this->_object->disablePregQuote()
            ->setPattern('foo/bar');
        $this->assertEquals('foo/bar', $this->_object->getPattern());

        $this->_object->enablePregQuote()
            ->disablePregQuote()
            ->addPatternElement(
                array(
                 'foo/bar',
                 'w00t..w00t',
                )
            );
        $this->assertEquals('(foo/bar|w00t..w00t)', $this->_object->getPattern());

    }


    /**
     * Test enable and disable case sensitivity
     *
     * @covers SLiib_WebApp_Security_Rule::enableCaseSensitivity
     * @covers SLiib_WebApp_Security_Rule::disableCaseSensitivity
     *
     * @return void
     */
    public function testEnableAndDisableCaseSensitivity()
    {
        $this->_object->disableCaseSensitivity();
        $this->assertEquals('i', $this->_object->getFlags());

        $this->_object->enableCaseSensitivity();
        $this->assertEmpty($this->_object->getFlags());

    }


}
?>
