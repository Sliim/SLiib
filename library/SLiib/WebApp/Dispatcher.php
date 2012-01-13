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
 * @package    SLiib\WebApp
 * @subpackage Dispatcher
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp;

/**
 * \SLiib\WebApp\Dispatcher
 *
 * @package    SLiib\WebApp
 * @subpackage Dispatcher
 */
class Dispatcher
{

    /**
     * Application namespace
     * @var \string
     */
    private static $_namespace = NULL;


    /**
     * Init dispatcher
     *
     * @param \string $namespace Application namespace
     *
     * @return \void
     */
    public static function init($namespace)
    {
        static::$_namespace = $namespace;

    }


    /**
     * Dispatching..
     *
     * @throws Exception\NoDispatchable
     *
     * @return \void
     */
    public static function dispatch()
    {
        $request    = Request::getInstance();
        $action     = $request->getAction();
        $controller = $request->getController();

        $controllerName = sprintf(
            "\\%s\\Controller\\%s",
            static::$_namespace,
            ucfirst($controller)
        );

        if (!class_exists($controllerName)) {
            throw new Exception\NoDispatchable(
                'Controller `' . $controllerName . '` doesn\'t exist.'
            );
        }

        $c = new $controllerName($controller, $action);
        $c->$action();

    }


}
