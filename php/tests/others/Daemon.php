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
require_once 'SLiib/Daemon.php';
/**
 * Test Class for SLiib_Daemon
 *
 * @package SLiib_Daemon
 */
class Daemon extends SLiib_Daemon
{


  /**
   * Daemon runner
   *
   * @return void
   */
  public function run()
  {
    $pid = $this->launch('daemonCode');
    sleep(50);
    echo 'Killing..' . PHP_EOL;
    $this->kill($pid);
    echo 'done.' . PHP_EOL;

  }


  /**
   * Code du Daemon
   *
   * @return void
   */
  public function daemonCode()
  {
    $i = 4;
    while ($i != 0) {
      $i--;
      sleep(5);
      echo $i . PHP_EOL;
    }

    echo 'End of Daemon Code!' . PHP_EOL;

  }


}

$daemon = new Daemon();
$daemon->run();
