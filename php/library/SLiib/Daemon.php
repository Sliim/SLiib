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
   * @var array $_daemons
   */
  private $_daemons = array();

  /**
   * PID du parent
   * @var int $_parentPID
   */
  private $_parentPID;


  /**
   * Constructeur, initialise le PID du père des démons
   *
   * @return void
   */
  public function __construct()
  {
    $this->_parentPID = getmypid();

    pcntl_signal(SIGTERM, array($this, '_handler'));

  }


  /**
   * Créé un démon
   *
   * @param string $daemonCode Nom de la fonction comportant le code à
   *                           exécuter par le démon.
   *
   * @throws SLiib_Daemon_Exception
   * @throws SLiib_Daemon_Exception_BadMethod
   *
   * @return bool
   */
  public function launch($daemonCode)
  {
    $pid = pcntl_fork();
    if ($pid == -1) {
      throw new SLiib_Daemon_Exception('Could not launch new daemon.');
    } else if ($pid) {
      $this->_daemons[] = $pid;
    } else {
      $pid = getmypid();
      if (!method_exists($this, $daemonCode)) {
        throw new SLiib_Daemon_Exception_BadMethod('Daemon function unknown.');
      }

      $this->$daemonCode();
    }

    return $pid;

  }


  /**
   * Tue un démon
   *
   * @param int           $pid    PID du démon à tuer
   * @param int[optional] $signal Signal à envoyer au daemon
   *
   * @throws SLiib_Daemon_Exception
   *
   * @return bool
   */
  public function kill($pid, $signal=SIGKILL)
  {
    if (!in_array($pid, $this->_daemons) || !is_numeric($pid)) {
      throw new SLiib_Daemon_Exception(
          'No daemon with PID ' . $pid . ' has launched.'
      );
    }

    return posix_kill($pid, $signal);

  }


  /**
   * Signal handler
   *
   * @param int $signal Signal
   *
   * @return void
   */
  protected function _handler($signal)
  {
    switch ($signal) {
      case SIGTERM:
        echo 'Daemon killed with SIGTERM!' . PHP_EOL;
          break;
      default:
        echo 'Daemon killed!' . PHP_EOL;
          break;
    }

  }


}