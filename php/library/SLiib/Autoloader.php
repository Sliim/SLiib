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
 * @package  SLiib_Autoloader
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Autoloader
 *
 * @package SLiib_Autoloader
 */
class SLiib_Autoloader
{

    /**
     * Classes chargées par l'autoloader
     * @var array $_isLoaded
     */
    private static $_isLoaded = array();

    /**
     * Namespaces autorisées.
     * @var array $_namespaces
     */
    private static $_namespaces = array();

    /**
     * Clés des namespaces.
     * @var array $_namespacesKeys
     */
    private static $_namespacesKeys = array();

    /**
     * Sections spéciales pour la génération des chemins de fichiers
     * @var $_sections
     */
    private static $_sections = array();


    /**
     * Initialisation de l'autoloader
     *
     * @param array           $namespaces Namespaces autorisés pour l'autoload
     * @param array[optional] $sections   Sections autorisées pour l'autoload
     *
     * @return void
     */
    public static function init(array $namespaces, array $sections=array())
    {
        if (!empty(static::$_namespaces)) {
            static::$_namespaces = array_merge(static::$_namespaces, $namespaces);
        } else {
            static::$_namespaces = $namespaces;
        }

        if (!empty(static::$_sections)) {
            static::$_sections = array_merge(static::$_sections, $sections);
        } else {
            static::$_sections = $sections;
        }

        static::$_namespacesKeys = array_keys(static::$_namespaces);

        spl_autoload_register(array(__CLASS__, 'autoload'));

    }


    /**
     * Auto-chargement d'une classe
     *
     * @param string $class Classe à charger.
     *
     * @return bool
     */
    public static function autoload($class)
    {
        if (in_array($class, static::$_isLoaded)) {
            return true;
        }

        $segment = explode('_', $class);
        if (count($segment) < 2) {
            return false;
        }

        $namespace = array_shift($segment);

        if (!in_array($namespace, static::$_namespacesKeys)) {
            return false;
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

        //TODO Check include result
        include $file;

        array_push(static::$_isLoaded, $class);
        return true;

    }


}
