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
 * @package  SLiib\Autoloader
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\Autoloader
 *
 * @package SLiib\Autoloader
 */
class Autoloader
{

    /**
     * Array of already autoloaded class
     * @var array
     */
    private static $isLoaded = array();

    /**
     * Allowed namespaces
     * @var array
     */
    private static $namespaces = array();

    /**
     * Namespaces keys
     * @var array
     */
    private static $namespacesKeys = array();

    /**
     * Sections collection
     * @var array
     */
    private static $sections = array();

    /**
     * Autoloader init
     *
     * @param array $namespaces Allowed namespaces
     * @param array $sections   Allowed sections
     *
     * @return void
     */
    public static function init(array $namespaces, array $sections = array())
    {
        static::$namespaces = array_merge(static::$namespaces, $namespaces);

        if (!empty(static::$sections)) {
            foreach ($sections as $key => $section) {
                if (array_key_exists($key, static::$sections)) {
                    static::$sections[$key] = array_merge(static::$sections[$key], $section);
                } else {
                    static::$sections[$key] = $section;
                }
            }
        } else {
            static::$sections = $sections;
        }

        static::$namespacesKeys = array_keys(static::$namespaces);

        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Class autoloader
     *
     * @param string $class Class to load
     *
     * @return boolean
     */
    public static function autoload($class)
    {
        if (in_array($class, static::$isLoaded)) {
            return true;
        }

        str_replace('_', '\\', $class);
        $segment = explode('\\', $class);
        if (count($segment) < 2) {
            return false;
        }

        $namespace = array_shift($segment);

        if (!in_array($namespace, static::$namespacesKeys)) {
            return false;
        }

        foreach (static::$sections as $ns => $sections) {
            if ($ns == $namespace) {
                foreach ($sections as $sectionKey => $sectionValue) {
                    if (in_array($sectionKey, $segment)) {
                        $key           = array_search($sectionKey, $segment);
                        $segment[$key] = $sectionValue;
                    }
                }
            }
        }

        $file =
          static::$namespaces[$namespace] . DIRECTORY_SEPARATOR .
          implode(DIRECTORY_SEPARATOR, $segment) . '.php';

        if (!static::searchForInclude($file)) {
            return false;
        }

        include $file;

        array_push(static::$isLoaded, $class);

        return true;
    }

    /**
     * Search a file to include
     *
     * @param string $needle File to search
     *
     * @return boolean
     */
    private static function searchForInclude($needle)
    {
        if (file_exists($needle)) {
            return true;
        }

        $includePath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($includePath as $path) {
            $file = realpath($path . '/' . $needle);
            if ($file && file_exists($file)) {
                return true;
            }
        }

        return false;
    }
}

