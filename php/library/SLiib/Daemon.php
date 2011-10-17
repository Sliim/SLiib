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
 * @package  SLiib_Daemon
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Daemon
 * 
 * @package SLiib_Daemon
 */
abstract class SLiib_Daemon
{

  /**
   * Tableau comprennant les PID des différents démons créés.
   * 
   * @var array
   */
  private $_daemons = array();

  /**
   * PID du parent
   * 
   * @var int
   */
  private $_parentPID;


  /**
   * Constructeur, initialise le PID du père des démons
   */
  public function __construct()
  {
    $this->_parentPID = getmypid();

  }


  /**
   * Créé un démon
   * 
   * @param string $daemonFunction Nom de la fonction comportant le code à
   *                               exécuter par le démon.
   * 
   * @return bool
   */
  public function launch($daemonFunction)
  {
    $pid = pcntl_fork();
    if ($pid == -1) {
      throw new SLiib_Daemon_Exception('Could not launch new daemon.');
    } else if ($pid) {
      $this->_daemons[] = $pid;
    } else {
      $pid = getmypid();
      if (!method_exists($this, $daemonFunction)) {
        throw new SLiib_Daemon_BadMethodException('Daemon function unknown.');
      }
      $this->$daemonFunction();
    }

    return $pid;

  }


  /**
   * Tue un démon
   * 
   * @param int $pid PID du démon à tuer
   * 
   * @return bool
   */
  public function kill($pid)
  {
    if (!in_array($pid, $this->_daemons) || !is_numeric($pid)) {
      throw new SLiib_Daemon_Exception(
          'No daemon with PID ' . $pid . ' has launched.'
      );
    }

    //TODO Revoir ça c'est crade!
    exec('kill ' . $pid, $out, $code);
    if ($code == 0)
      return true;
    return false;

  }


}