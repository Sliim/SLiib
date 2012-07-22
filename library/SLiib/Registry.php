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
 * @category SLiib
 * @package  SLiib\Registry
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\Registry
 *
 * @package SLiib\Registry
 */
class Registry
{
    /**
     * Registry singleton
     * @var array
     */
    private static $_registry = array();

    /**
     * Get registry balue from a key
     *
     * @param string $key Key to get
     *
     * @throws \SLiib\Registry\Exception
     *
     * @return mixed
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$_registry)) {
            throw new Registry\Exception('Key ' . $key . ' not found in registry.');
        }

        return self::$_registry[$key];
    }

    /**
     * Set a value to a key
     *
     * @param string $key   Key to set
     * @param mixed  $value Value to assign
     *
     * @throws \SLiib\Registry\Exception
     *
     * @return void
     */
    public static function set($key, $value)
    {
        if (array_key_exists($key, self::$_registry)) {
            throw new Registry\Exception('Key ' . $key . ' already exist.');
        }

        self::$_registry[$key] = $value;
    }
}

