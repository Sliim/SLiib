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
 * @subpackage ApplicationTest
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace Test;

use SLiib\WebApp\Bootstrap as WebAppBootstrap;
use SLiib\WebApp\Exception as WebAppException;
use SLiib\WebApp\Security\Checker;

/**
 * test Boostrap
 *
 * @package    Tests
 * @subpackage ApplicationTest
 */
class Bootstrap extends WebAppBootstrap
{
    /**
     * Init application's namespaces & sections
     *
     * @return void
     */
    public function init()
    {
        $this->setNamespaces(
            array(
             'SLiib' => 'SLiib',
             'Lib'   => ROOT_PATH . '/library/Test',
            )
        );

        $this->setSections(
            array(
             'Test' => array(
                        'Model'      => 'models',
                        'Controller' => 'controllers',
                       )
            )
        );

        try {
            $this->setViewPath(APP_PATH . '/views_not_exists');
        } catch (WebAppException $e) {
            $this->setViewPath(APP_PATH . '/views');
        }

        $allowedMethod = new Checker\AllowedMethods();
        $allowedMethod->getRule(1200)->addPatternElement('1337');

        $this->setSecurityCheckers(
            array(
             0 => new Checker\PHPCodeInject(),
             1 => new Checker\FilenamePolicy(),
             2 => $allowedMethod,
            )
        );

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
     * @throws \RuntimeException
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

        return $this->exceptionHandler(new \RuntimeException($message, $errno));
    }


    /**
     * Exception Handler
     *
     * @param \Exception $e The exception object
     *
     * @return void
     */
    protected function exceptionHandler(\Exception $e)
    {
        return parent::exceptionhandler($e);
    }
}
