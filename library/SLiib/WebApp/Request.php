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
 * @package    SLiib\WebApp
 * @subpackage Request
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp;

/**
 * \SLiib\WebApp\Request
 *
 * @package    SLiib\WebApp
 * @subpackage Request
 */
class Request
{

    /**
     * Request instance
     * @var \SLiib\WebApp\Request
     */
    private static $_instance = NULL;

    /**
     * Request data
     * @var stdClass
     */
    private $_request = NULL;


    /**
     * Init HTTP Request
     *
     * @return void
     */
    public static function init()
    {
        static::$_instance = new self();

    }


    /**
     * Instance getter
     *
     * @throws \SLiib\WebApp\Request\Exception
     *
     * @return \SLiib\WebApp\Request
     */
    public static function getInstance()
    {
        if (is_null(static::$_instance)) {
            throw new Request\Exception(
                'Request not initialized.'
            );
        }

        return static::$_instance;

    }


    /**
     * Get current controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->_request->controller;

    }


    /**
     * Get current action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_request->action;

    }


    /**
     * Get request URI
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $this->_request->requestUri;

    }


    /**
     * Get current parameters
     *
     * @return array
     */
    public function getParameters()
    {
        switch ($this->_request->method) {
            case 'GET':
                return $this->_request->paramsGet;
            case 'POST':
                return $this->_request->paramsPost;
            default:
                return array();
        }

    }


    /**
     * Client ip getter
     *
     * @return string
     */
    public function getClientIp()
    {
        return $this->_request->clientIp;

    }


    /**
     * User agent getter
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->_request->userAgent;

    }


    /**
     * Request method getter
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->_request->method;

    }


    /**
     * Cookies getter
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->_request->cookies;

    }


    /**
     * Referer getter
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->_request->referer;

    }


    /**
     * Check is request use post method
     *
     * @return boolean
     */
    public function isPost()
    {
        if ($this->getRequestMethod() === 'POST') {
            return TRUE;
        }

        return FALSE;

    }


    /**
     * Construct request
     *
     * @return void
     */
    private function __construct()
    {
        $this->_request = new stdClass;
        $this->_initProperties();

    }


    /**
     * Init HTTP Properties
     *
     * @return void
     */
    private function _initProperties()
    {
        $this->_request->clientIp =
            array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : NULL;

        $this->_request->userAgent =
            array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : NULL;

        $this->_request->method =
            array_key_exists('REQUEST_METHOD', $_SERVER) ? $_SERVER['REQUEST_METHOD'] : NULL;

        $this->_request->referer =
            array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : NULL;

        $this->_request->requestUri =
            array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '/';

        $get = $this->_parseUrl();

        $this->_request->controller = $get['controller'];
        $this->_request->action     = $get['action'];
        $this->_request->paramsGet  = $get['params'];
        $this->_request->paramsPost = $_POST;
        $this->_request->cookies    = $_COOKIE;

    }


    /**
     * Url parser
     *
     * @return array
     */
    private function _parseUrl()
    {
        $controller = '';
        $action     = '';
        $params     = array();

        if ($this->_request->requestUri == '/') {
            $controller = 'index';
            $action     = 'index';
        } else {
            $segment = explode('/', $this->_request->requestUri);
            array_shift($segment);

            if (count($segment) >= 2 && !empty($segment[1])) {
                $controller = array_shift($segment);
                $action     = array_shift($segment);
            } else {
                $controller = $segment[0];
                $action     = 'index';
            }

            $controller = $this->_transformDash($controller);
            $action     = $this->_transformDash($action);

            $key = NULL;

            foreach ($segment as $seg) {
                if (!is_null($key)) {
                    $params[$this->_transformDash($key)] = $seg;

                    $key = NULL;
                } else {
                    $key = (string) $seg;
                }
            }
        }

        return array(
                'controller' => $controller,
                'action'     => $action,
                'params'     => $params,
               );

    }


    /**
     * Transform a string with a dash
     * my-string become myString
     *
     * @param string $string String to transform
     *
     * @return string
     */
    private function _transformDash($string)
    {
        $pos = strpos($string, '-');

        if (FALSE !== $pos) {
            $len    = strlen($string) - 1;
            $string = substr($string, 0, $pos) . ucfirst(substr($string, $pos + 1, $len - $pos));

            if (strpos($string, '-') !== FALSE) {
                $string = $this->_transformDash($string);
            }
        }

        return $string;

    }


}
