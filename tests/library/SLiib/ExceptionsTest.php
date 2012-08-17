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
 * PHP version 5.3
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
 * Test class for all SLiib exceptions
 *
 * @package    Tests
 * @subpackage UnitTests
 */
class ExceptionsTest extends \PHPUnit_Framework_TestCase
{
    const EXCEPTION_NAMESPACE = '\\SLiib\\';
    const EXCEPTION_RUNTIME   = 'IException\\Runtime';
    const EXCEPTION_LOGIC     = 'IException\\Logic';

    /**
     * Exceptions list to test
     * @var array
     */
    private static $e = array();

    /**
     * Set up exceptions list
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$e = array(
                     'Config\\Exception'                         => array('Exception'),
                     'Config\\Exception\\SyntaxError'            => array(
                                                                     'Config\\Exception',
                                                                     self::EXCEPTION_LOGIC,
                                                                    ),
                     'Config\\Exception\\UndefinedProperty'      => array(
                                                                     'Config\\Exception',
                                                                     self::EXCEPTION_LOGIC,
                                                                    ),
                     'Listing\\Exception'                        => array('Exception'),
                     'Log\\Exception'                            => array('Exception'),
                     'Registry\\Exception'                       => array('Exception'),
                     'SolR\\Exception'                           => array('Exception'),
                     'SystemInfos\\Exception'                    => array('Exception'),
                     'SystemInfos\\Exception\\BadMethodCall'     => array(
                                                                     'SystemInfos\\Exception',
                                                                     self::EXCEPTION_LOGIC,
                                                                    ),
                     'SystemInfos\\Exception\\CommandFailed'     => array(
                                                                     'SystemInfos\\Exception',
                                                                     self::EXCEPTION_RUNTIME,
                                                                    ),
                     'WebApp\\Exception'                         => array('Exception'),
                     'WebApp\\Request\\Exception'                => array('WebApp\\Exception'),
                     'WebApp\\Session\\Exception'                => array('WebApp\\Exception'),
                     'WebApp\\Security\\Exception'               => array('WebApp\\Exception'),
                     'WebApp\\Exception\\InvalidParameter'       => array(
                                                                     'WebApp\\Exception',
                                                                     self::EXCEPTION_LOGIC,
                                                                    ),
                     'WebApp\\Exception\\NoDispatchable'         => array(
                                                                     'WebApp\\Exception',
                                                                     self::EXCEPTION_RUNTIME,
                                                                    ),
                     'WebApp\\Exception\\UndefinedProperty'      => array(
                                                                     'WebApp\\Exception',
                                                                     self::EXCEPTION_LOGIC,
                                                                    ),
                     'WebApp\\Security\\Exception\\CheckerError' => array(
                                                                     'WebApp\\Security\\Exception',
                                                                     self::EXCEPTION_RUNTIME,
                                                                    ),
                    );

    }

    /**
     * Test all instance of exceptions
     *
     * @return void
     */
    public function testInstanceOf()
    {
        //Base exception
        $e = new \SLiib\Exception;
        $this->assertInstanceOf('\\Exception', $e);

        foreach (self::$e as $exception => $instances) {
            $class = self::EXCEPTION_NAMESPACE . $exception;
            $e     = new $class();

            foreach ($instances as $instance) {
                $this->assertInstanceOf(self::EXCEPTION_NAMESPACE . $instance, $e);

                switch ($instance) {
                    case self::EXCEPTION_LOGIC:
                        $this->assertNotInstanceOf(
                            self::EXCEPTION_NAMESPACE . self::EXCEPTION_RUNTIME,
                            $e
                        );
                        break;
                    case self::EXCEPTION_RUNTIME:
                        $this->assertNotInstanceOf(
                            self::EXCEPTION_NAMESPACE . self::EXCEPTION_LOGIC,
                            $e
                        );
                        break;
                    default:
                        //Nothing to do
                        break;
                }
            }
        }
    }
}

