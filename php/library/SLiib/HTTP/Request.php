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

/**
 * SLiib_HTTP_Request
 *
 * @package SLiib_HTTP_Request
 */
class SLiib_HTTP_Request
{

    /**
     * Current controller
     * @var string
     */
    private static $_controller = null;

    /**
     * Current action
     * @var string
     */
    private static $_action = null;

    /**
     * Current parameters
     * @var array
     */
    private static $_params = null;

    /**
     * Request URI
     * @var string
     */
    private static $_requestUri = '/';

    /**
     * Client IP
     * @var string
     */
    private static $_clientIp = null;

    /**
     * Client user agent
     * @var string
     */
    private static $_userAgent = null;

    /**
     * HTTP method
     * @var string
     */
    private static $_method = null;

    /**
     * Cookies
     * @var array
     */
    private static $_cookies = null;

    /**
     * Referer
     * @var string
     */
    private static $_referer = null;


    /**
     * Init HTTP Request
     *
     * @return void
     */
    public static function init()
    {
        static::_initProperties();
        $params     = array();

        if (static::$_requestUri == '/') {
            static::$_controller = 'index';
            static::$_action     = 'index';
        } else {
            $segment = explode('/', static::$_requestUri);
            array_shift($segment);

            if (count($segment) >= 2) {
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


    /**
     * Client ip getter
     *
     * @return string
     */
    public static function getClientIp()
    {
        return static::$_clientIp;

    }


    /**
     * User agent getter
     *
     * @return string
     */
    public static function getUserAgent()
    {
        return static::$_userAgent;

    }


    /**
     * Request method getter
     *
     * @return string
     */
    public static function getRequestMethod()
    {
        return static::$_method;

    }


    /**
     * Cookies getter
     *
     * @return array
     */
    public static function getCookies()
    {
        return static::$_cookies;

    }


    /**
     * Referer getter
     *
     * @return string
     */
    public static function getReferer()
    {
        return static::$_referer;

    }


    /**
     * Init HTTP Properties
     *
     * @return void
     */
    private static function _initProperties()
    {
        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            static::$_clientIp = $_SERVER['REMOTE_ADDR'];
        }

        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            static::$_userAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        if (array_key_exists('REQUEST_METHOD', $_SERVER)) {
            static::$_method = $_SERVER['REQUEST_METHOD'];
        }

        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            static::$_referer = $_SERVER['HTTP_REFERER'];
        }

        if (array_key_exists('REQUEST_URI', $_SERVER)) {
            static::$_requestUri = $_SERVER['REQUEST_URI'];
        }

        static::$_cookies = $_COOKIE;

    }


}
