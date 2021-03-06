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
 * @subpackage Session
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
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
     * @var boolean
     */
    private static $started = false;

    /**
     * Session values
     * @var array
     */
    private $session = array();

    /**
     * Session namespace
     * @var string
     */
    private $namespace;

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
        if (!static::started()) {
            throw new Session\Exception('Session not initialized.');
        }

        $this->namespace = $namespace;

        if (!array_key_exists($namespace, $_SESSION)) {
            $this->updateSession();
        } else {
            $this->session = $_SESSION[$namespace];
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
        if (!array_key_exists($name, $this->session)) {
            throw new Session\Exception('Session has not `' . $name . '` index');
        }

        return $this->session[$name];
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
        $this->session[$name] = $value;
        $this->updateSession();
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
        if (!array_key_exists($name, $this->session)) {
            throw new Session\Exception('Session has not `' . $name . '` index');
        }

        unset($this->session[$name]);
        $this->updateSession();
    }

    /**
     * Check if property exist
     *
     * @param string $name Property name
     *
     * @return boolean
     */
    public function __isset($name)
    {
        if (!array_key_exists($name, $this->session)) {
            return false;
        }

        return true;
    }

    /**
     * Session init
     *
     * @return void
     */
    public static function init()
    {
        if (!static::started()) {
            session_start();
        }

        static::$started = true;
    }

    /**
     * Session destroy
     *
     * @return void
     */
    public static function destroy()
    {
        if (session_id() !== '') {
            session_destroy();
        }

        static::$started = false;
    }

    /**
     * Return session status
     *
     * @return boolean
     */
    public static function started()
    {
        if (session_id() !== '' || headers_sent()) {
            return true;
        }

        return static::$started;
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
            return true;
        }

        return false;
    }

    /**
     * Clear current session namespace
     *
     * @return void
     */
    public function clear()
    {
        $this->session = array();

        if (array_key_exists($this->namespace, $_SESSION)) {
            unset($_SESSION[$this->namespace]);
        }
    }

    /**
     * Update global variable $_SESSION
     *
     * @return void
     */
    private function updateSession()
    {
        $_SESSION[$this->namespace] = $this->session;
    }
}
