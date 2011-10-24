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
 * @category SLiib
 * @package  SLiib_Dispatcher
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Dispatcher
 *
 * @package SLiib_Dispatcher
 */
class SLiib_Dispatcher
{

    /**
     * Application namespace
     * @var string $_namespace
     */
    private static $_namespace = null;


    /**
     * Init dispatcher
     *
     * @param string $namespace Application namespace
     *
     * @return void
     */
    public static function init($namespace)
    {
        static::$_namespace = $namespace;

    }


    /**
     * Dispatching..
     *
     * @throws SLiib_Dispatcher_Exception
     *
     * @return void
     */
    public static function dispatch()
    {
        $action = SLiib_HTTP_Request::getAction();
        $ctrl   = ucfirst(SLiib_HTTP_Request::getController());

        $controllerName = sprintf(
            "%s_Controller_%s",
            static::$_namespace,
            $ctrl
        );

        $controller = new $controllerName();
        $controller->$action();

    }


}
