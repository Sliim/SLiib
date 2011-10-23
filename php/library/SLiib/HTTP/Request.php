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
 * @package  SLiib_HTTP_Request
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */
require_once 'SLiib/Autoloader.php';

/**
 * SLiib_HTTP_Request
 *
 * @package SLiib_HTTP_Request
 */
class SLiib_HTTP_Request
{

    /**
     * Current controller
     * @var string $_controller
     */
    private static $_controller = null;

    /**
     * Current action
     * @var string $_action
     */
    private static $_action = null;

    /**
     * Current parameters
     * @var array
     */
    private static $_params = null;


    /**
     * Init HTTP Request
     *
     * @return void
     */
    public static function init()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $params     = array();

        if ($requestUri == '/') {
            static::$_controller = 'index';
            static::$_action     = 'index';
        } else {
            $segment = explode('/', $requestUri);
            array_shift($segment);

            if ($segment >= 2) {
                static::$_controller = array_shift($segment);
                static::$_action     = array_shift($segment);
            } else {
                static::$_controller = $segment[0];
                static::$_action     = 'index';
            }

            $key = null;
            foreach ($segment as $seg) {
                if (!is_null($key)) {
                    $params[$key] = $seg;
                    $key          = null;
                } else {
                    $key = $seg;
                }
            }
        }

        static::$_params = array_merge(
            $_POST,
            $params
        );

    }


    /**
     * Get current controller
     *
     * @return string
     */
    public static function getController()
    {
        return static::$_controller;

    }


    /**
     * Get current action
     *
     * @return string
     */
    public static function getAction()
    {
        return static::$_action;

    }


    /**
     * Get current parameters
     *
     * @return array
     */
    public static function getParameters()
    {
        return static::$_params;

    }


}
