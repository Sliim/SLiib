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
 * @subpackage Tests
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * test Boostrap
 *
 * @package    SLiib
 * @subpackage Tests
 */
class Test_Bootstrap extends SLiib_Application_Bootstrap
{


    /**
     * Init application's namespaces & sections
     *
     * @return void
     */
    public function init()
    {
        $this->_setNamespaces(
            array(
             'SLiib' => 'SLiib',
             'Lib'   => ROOT_PATH . '/library/Test',
            )
        );

        $this->_setSections(
            array(
             'Test' => array(
                        'Model'      => 'models',
                        'Controller' => 'controllers',
                       )
            )
        );

        $this->_setViewPath(APP_PATH . '/views');

        error_reporting(E_ALL | E_STRICT);
        set_error_handler(array($this, 'errorHandler'), E_ALL | E_STRICT);

    }


    /**
     * Error handler
     *
     * @param int    $errno   Error number
     * @param string $errstr  Error message
     * @param string $errfile Error file
     * @param int    $errline Error line
     *
     * @throws RuntimeException
     *
     * @return void
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $message = sprintf(
            "%s in `%s` on line %d",
            $errstr,
            $errfile,
            $errline
        );

        throw new RuntimeException($message, $errno);

    }


}