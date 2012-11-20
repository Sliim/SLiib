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
 * @subpackage Request
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
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
    private static $instance = null;

    /**
     * Request data
     * @var stdClass
     */
    private $request = null;

    /**
     * Init HTTP Request
     *
     * @return void
     */
    public static function init()
    {
        static::$instance = new self();
    }

    /**
     * Instance getter
     *
     * @throws Request\Exception
     *
     * @return \SLiib\WebApp\Request
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            throw new Request\Exception(
                'Request not initialized.'
            );
        }

        return static::$instance;
    }

    /**
     * Get current controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->request->controller;
    }

    /**
     * Get current action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->request->action;
    }

    /**
     * Get request URI
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $this->request->requestUri;
    }

    /**
     * Get current parameters
     *
     * @return array
     */
    public function getParameters()
    {
        switch ($this->request->method) {
            case 'GET':
                return $this->request->paramsGet;
            case 'POST':
                return $this->request->paramsPost;
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
        return $this->request->clientIp;
    }

    /**
     * User agent getter
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->request->userAgent;
    }

    /**
     * Request method getter
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->request->method;
    }

    /**
     * Cookies getter
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->request->cookies;
    }

    /**
     * Referer getter
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->request->referer;
    }

    /**
     * Check is request use post method
     *
     * @return boolean
     */
    public function isPost()
    {
        if ($this->getRequestMethod() === 'POST') {
            return true;
        }

        return false;
    }

    /**
     * Construct request
     *
     * @return void
     */
    private function __construct()
    {
        $this->request = new \stdClass;
        $this->initProperties();
    }

    /**
     * Init HTTP Properties
     *
     * @return void
     */
    private function initProperties()
    {
        $this->request->clientIp =
            array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : null;

        $this->request->userAgent =
            array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : null;

        $this->request->method =
            array_key_exists('REQUEST_METHOD', $_SERVER) ? $_SERVER['REQUEST_METHOD'] : null;

        $this->request->referer =
            array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : null;

        $this->request->requestUri =
            array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '/';

        $get = $this->parseUrl();

        $this->request->controller = $get['controller'];
        $this->request->action     = $get['action'];
        $this->request->paramsGet  = $get['params'];
        $this->request->paramsPost = $_POST;
        $this->request->cookies    = $_COOKIE;
    }

    /**
     * Url parser
     *
     * @return array
     */
    private function parseUrl()
    {
        $controller = '';
        $action     = '';
        $params     = array();

        if ($this->request->requestUri == '/') {
            $controller = 'index';
            $action     = 'index';
        } else {
            $segment = explode('/', $this->request->requestUri);
            array_shift($segment);

            if (count($segment) >= 2 && !empty($segment[1])) {
                $controller = array_shift($segment);
                $action     = array_shift($segment);
            } else {
                $controller = $segment[0];
                $action     = 'index';
            }

            $controller = $this->transformDash($controller);
            $action     = $this->transformDash($action);

            $key = null;

            foreach ($segment as $seg) {
                if (!is_null($key)) {
                    $params[$this->transformDash($key)] = $seg;

                    $key = null;
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
    private function transformDash($string)
    {
        $pos = strpos($string, '-');

        if (false !== $pos) {
            $len    = strlen($string) - 1;
            $string = substr($string, 0, $pos) . ucfirst(substr($string, $pos + 1, $len - $pos));

            if (strpos($string, '-') !== false) {
                $string = $this->transformDash($string);
            }
        }

        return $string;
    }
}
