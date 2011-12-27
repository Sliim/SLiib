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
 * @package  SLiib\Autoloader
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * Namespace
 */
namespace SLiib;

/**
 * SLiib\Autoloader
 *
 * @package SLiib\Autoloader
 */
class Autoloader
{

    /**
     * Array of class loaded
     * @var array
     */
    private static $_isLoaded = array();

    /**
     * Allowed namespaces
     * @var array
     */
    private static $_namespaces = array();

    /**
     * Namespaces keys
     * @var array
     */
    private static $_namespacesKeys = array();

    /**
     * Sections collection
     * @var array
     */
    private static $_sections = array();


    /**
     * Autoloader init
     *
     * @param array           $namespaces Allowed namespaces
     * @param array[optional] $sections   Allowed sections
     *
     * @return void
     */
    public static function init(array $namespaces, array $sections=array())
    {
        static::$_namespaces = array_merge(static::$_namespaces, $namespaces);

        if (!empty(static::$_sections)) {
            foreach ($sections as $key => $section) {
                if (array_key_exists($key, static::$_sections)) {
                    static::$_sections[$key] = array_merge(static::$_sections[$key], $section);
                } else {
                    static::$_sections[$key] = $section;
                }
            }
        } else {
            static::$_sections = $sections;
        }

        static::$_namespacesKeys = array_keys(static::$_namespaces);

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
        if (in_array($class, static::$_isLoaded)) {
            return TRUE;
        }

        str_replace('_', '\\', $class);
        $segment = explode('\\', $class);
        if (count($segment) < 2) {
            return FALSE;
        }

        $namespace = array_shift($segment);

        if (!in_array($namespace, static::$_namespacesKeys)) {
            return FALSE;
        }

        foreach (static::$_sections as $ns => $sections) {
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
          static::$_namespaces[$namespace] . DIRECTORY_SEPARATOR .
          implode(DIRECTORY_SEPARATOR, $segment) . '.php';

        if (!static::_searchForInclude($file)) {
            return FALSE;
        }

        include $file;

        array_push(static::$_isLoaded, $class);
        return TRUE;

    }


    /**
     * Search a file to include
     *
     * @param string $needle File to search
     *
     * @return boolean
     */
    private static function _searchForInclude($needle)
    {
        if (file_exists($needle)) {
            return TRUE;
        }

        $includePath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($includePath as $path) {
            $file = realpath($path . '/' . $needle);
            if ($file && file_exists($file)) {
                return TRUE;
            }
        }

        return FALSE;

    }


}
