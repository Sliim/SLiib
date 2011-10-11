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
 * @version  Release: 0.1
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
   * @var SLiib_Autoloader $_instance Instance de l'autoloader
   */
  private static $_instance = null;

  /**
   * @var array $_isLoaded Classes chargées par l'autoloader
   */
  private static $_isLoaded = array();

  /**
   * @var array $_namespaces Namespaces autorisés.
   */
  private static $_namespaces = array();


  /**
   * Initialisation de l'autoloader
   * 
   * @param array $namespaces Namespaces autorisée pour l'autoload
   * 
   * @return void
   */
  public static function init($namespaces)
  {
    if (static::$_instance != null) {
      throw new SLiib_Autoloader_Exception('Autoloader already initialized.');
    }

    spl_autoload_register(array(__CLASS__, 'autoload'));

    static::$_namespaces = $namespaces;
    static::$_instance   = new self();

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

    $seq = explode('_', $class);
    if (count($seq) < 2) {
      return false;
    }

    $namespace = $seq[0];

    if (!in_array($namespace, static::$_namespaces)) {
      return false;
    }

    $file = implode(DIRECTORY_SEPARATOR, $seq) . '.php';

    try {
      include $file;
    } catch (Exception $e) {
      return false;
    }

    array_push(static::$_isLoaded, $class);
    return true;

  }


}
