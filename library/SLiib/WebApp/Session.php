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
 * @subpackage Session
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp;

/**
 * \SLiib\WebApp\Session
 *
 * @package    SLiib\WebApp
 * @subpackage Session
 */
class Session
{

    /**
     * Session started
     * @var bool
     */
    private static $_started = FALSE;

    /**
     * Session values
     * @var array
     */
    private $_session = array();

    /**
     * Session namespace
     * @var string
     */
    private $_namespace;


    /**
     * Construct
     *
     * @param string $namespace Session namespace
     *
     * @throws Session\Exception
     *
     * @return void
     */
    public function __construct($namespace)
    {
        if (!static::$_started) {
            throw new Session\Exception('Session not initialized.');
        }

        $this->_namespace = $namespace;

        if (!array_key_exists($namespace, $_SESSION)) {
            $this->_updateSession();
        } else {
            $this->_session = $_SESSION[$namespace];
        }

    }


    /**
     * Session's property getter
     *
     * @param string $name Property name
     *
     * @throws Session\Exception
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->_session)) {
            throw new Session\Exception('Session has not `' . $name . '` index');
        }

        return $this->_session[$name];

    }


    /**
     * Session's property setter
     *
     * @param string $name  Property name
     * @param mixed  $value Value to affect
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_session[$name] = $value;
        $this->_updateSession();

    }


    /**
     * Unset session's property
     *
     * @param string $name Property name
     *
     * @throws Session\Exception
     *
     * @return void
     */
    public function __unset($name)
    {
        if (!array_key_exists($name, $this->_session)) {
            throw new Session\Exception('Session has not `' . $name . '` index');
        }

        unset($this->_session[$name]);
        $this->_updateSession();

    }


    /**
     * Check if property exist
     *
     * @param string $name Property name
     *
     * @return bool
     */
    public function __isset($name)
    {
        if (!array_key_exists($name, $this->_session)) {
            return FALSE;
        }

        return TRUE;

    }


    /**
     * Session init
     *
     * @return void
     */
    public static function init()
    {
        if (!static::$_started) {
            session_start();
            static::$_started = TRUE;
        }

    }


    /**
     * Session destroy
     *
     * @return void
     */
    public static function destroy()
    {
        if (static::$_started) {
            session_destroy();
            static::$_started = FALSE;
        }

    }


    /**
     * Return session status
     *
     * @return boolean
     */
    public static function started()
    {
        return static::$_started;

    }


    /**
     * Check if a namespace exists
     *
     * @param string $namespace Namespace to check
     *
     * @return boolean
     */
    public static function namespaceExist($namespace)
    {
        if (array_key_exists($namespace, $_SESSION)) {
            return TRUE;
        }

        return FALSE;

    }


    /**
     * Clear current session namespace
     *
     * @return void
     */
    public function clear()
    {
        $this->_session = array();

        if (array_key_exists($this->_namespace, $_SESSION)) {
            unset($_SESSION[$this->_namespace]);
        }

    }


    /**
     * Update global variable $_SESSION
     *
     * @return void
     */
    private function _updateSession()
    {
        $_SESSION[$this->_namespace] = $this->_session;

    }


}
