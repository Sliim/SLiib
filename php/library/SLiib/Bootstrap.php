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
 * @package  SLiib_Bootstrap
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Bootstrap
 *
 * @package SLiib_Bootstrap
 */
abstract class SLiib_Bootstrap
{

  /**
   * Namespace de l'application
   * @var string $_appNamespace
   */
  protected static $_appNamespace;

  /**
   * Chemin de l'application
   * @var string $_appPath
   */
  protected static $_appPath;


  /**
   * Initialisation du bootstrap
   *
   * @param string $appNamespace Namespace de l'application
   * @param string $appPath      Chemin de l'application
   *
   * @return void
   */
  public static function init($appNamespace, $appPath)
  {
    static::$_appNamespace = $appNamespace;
    static::$_appPath      = $appPath;

  }


  /**
   * Démarrage du bootstrap
   *
   * @return void
   */
  public static function run()
  {
    static::_setEnvironment();

    //TODO SLiib_Dispatcher::dispatch()

  }


  /**
   * Initialise l'environnement de l'application
   *
   * @return void
   */
  protected static function _setEnvironment()
  {
    //TODO SLiib_HTTP_Session
    //TODO SLiib_HTTP_Request

  }


  /**
   * Gestion des erreurs de l'application
   *
   * @return void
   */
  protected static function _error()
  {

  }


}
