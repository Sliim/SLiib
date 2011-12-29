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

/**
 * Test class for \SLiib\SolR.
 * Generated by PHPUnit on 2011-10-17 at 20:19:49.
 *
 * Ce test s'appuie sur le schema SolR de Solrack
 * https://gitorious.org/solrack
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class SolRTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Objet de test
     * @var \SLiib\SolR
     */
    protected $_object;

    /**
     * Host
     * @var string
     */
    protected $_host = 'localhost';

    /**
     * Port
     * @var int
     */
    protected $_port = 8983;

    /**
     * XML string to update for test
     * @var string
     */
    protected $_xmlStr;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        try {
            $this->_object = new SolR($this->_host, $this->_port);
        } catch (SolR\Exception $e) {
            $this->markTestSkipped(
                'Ping to SolR failed at ' . $this->_host . ':' . $this->_port
            );
        }

        $this->_xmlStr  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $this->_xmlStr .= '<add><doc><field name="text">test indexing</field>';
        $this->_xmlStr .= '<field name="md5">d8e8fca2dc0f896fd7cb4cb0031ba249</field>';
        $this->_xmlStr .= '<field name="sha1">4e1243bd22c66e76c2ba9eddc1f91394e57f9f83';
        $this->_xmlStr .= '</field></doc></add>';

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
     * Test Ping
     *
     * @covers \SLiib\SolR::ping
     *
     * @return void
     */
    public function testPing()
    {
        $res = $this->_object->ping();
        $this->assertTrue($res);

    }


    /**
     * Test delete all
     *
     * @covers \SLiib\SolR::deleteAll
     *
     * @return void
     */
    public function testDeleteAll()
    {
        $this->_object->deleteAll();

    }


    /**
     * Test update
     *
     * @covers \SLiib\SolR::update
     *
     * @return void
     */
    public function testUpdate()
    {
        $res = $this->_object->update($this->_xmlStr);

        $this->assertTrue($res);

    }


    /**
     * Test commit
     *
     * @covers \SLiib\SolR::commit
     *
     * @return void
     */
    public function testCommit()
    {
        $this->_object->commit();

    }


    /**
     * Test get
     *
     * @covers \SLiib\SolR::get
     * @covers \SLiib\SolR::escapeSpecialChar
     *
     * @return void
     */
    public function testGet()
    {
        $query  = 'test';
        $res    = $this->_object->get($this->_object->escapeSpecialChar($query));
        $xmlRes = simplexml_load_string($res);

        $this->assertObjectHasAttribute('lst', $xmlRes);
        $this->assertObjectHasAttribute('result', $xmlRes);

    }


    /**
     * Test get total indexed
     *
     * @covers \SLiib\SolR::getTotalIndexed
     *
     * @return void
     */
    public function testGetTotalIndexed()
    {
        $res = $this->_object->getTotalIndexed();
        $this->assertGreaterThan(0, $res);

    }


    /**
     * Test with bad port
     *
     * @return void
     */
    public function testBadPort()
    {
        try {
            $object = new SolR($this->_host, 1337, TRUE);
        } catch (SolR\Exception $e) {
            $this->assertInstanceOf('\SLiib\SolR\Exception', $e);
        }

        $object = new SolR($this->_host, 1337, FALSE);

        $res = $object->ping();
        $this->assertFalse($res);

        $res = $object->update($this->_xmlStr);
        $this->assertFalse($res);

        $res = $object->get('*:*');
        $this->assertFalse($res);

        $res = $object->getTotalIndexed();
        $this->assertFalse($res);

    }


    /**
     * Test bad xml string
     *
     * @covers \SLiib\SolR::update
     * @covers \SLiib\SolR::get
     *
     * @return void
     */
    public function testBadXmlString()
    {
        $res = $this->_object->update('foo');
        $this->assertFalse($res);

        $res = $this->_object->get('***');
        $this->assertFalse($res);

    }


}