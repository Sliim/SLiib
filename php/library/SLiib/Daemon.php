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
 * @package  Library
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.1.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Daemon
 * 
 * @package Daemon
 */
class SLiib_Daemon
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
    echo 'Okay, parent PID is ' . $this->_parentPID . PHP_EOL;

  }


  /**
   * Créé un démon
   * 
   * @param string $daemonFunction Nom de la fonction comportant le code à
   *                               exécuter par le démon.
   * 
   * @return bool
   */
  public function launchDaemon($daemonFunction)
  {
    $pid = pcntl_fork();
    if ($pid == -1) {
      echo 'Could not launch new daemon' . PHP_EOL;
    } else if ($pid) {
      echo 'Launching new daemon with pid ' . $pid . PHP_EOL;
      $this->_daemons[] = $pid;
    } else {
      $pid = getmypid();
      echo 'Executing daemon\'s code of ' . $pid . PHP_EOL;
      $this->$daemonFunction();
      exit(0);
    }

    return $pid;

  }


  /**
   * Tue un démon créé
   * 
   * @param int $pid PID du démon à tuer
   * 
   * @return bool
   */
  public function killDaemon($pid)
  {
    if (!in_array($pid, $this->_daemons) || !is_numeric($pid)) {
      echo 'No daemon with PID ' . $pid . ' has launched.' . PHP_EOL;
      return false;
    }

    echo 'Killing daemon with PID ' . $pid . PHP_EOL;
    exec('kill ' . $pid, $out, $code);
    if ($code == 0)
      return true;
    return false;

  }


}


/*class Daemon_1 extends Daemon {
  public function run() {
    $pid = $this->launchDaemon('daemonCode');
    sleep(5);
    echo 'Killing..' . PHP_EOL;
    $this->killDaemon($pid);
    echo 'done' . PHP_EOL;
  }

  public function daemonCode() {
    sleep(10);
    echo '3' . PHP_EOL;
    sleep(10);
    echo '2' . PHP_EOL;
    sleep(10);
    echo '1' . PHP_EOL;
    sleep(10);
    echo '0' . PHP_EOL;
    echo 'End of daemon code!' . PHP_EOL;
  }
}*/
