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
 * @package  SLiib\SystemInfos
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;
use SLiib\SystemInfos\Interfaces;


/**
 * \SLiib\SystemInfos
 *
 * @package SLiib\SystemInfos
 */
class SystemInfos implements
    Interfaces\IUname,
    Interfaces\IPhp,
    Interfaces\IApache2,
    Interfaces\ILsbRelease
{


    /**
     * Static call methods, a constant must be defined with the command to execute
     *
     * @param \string $name      Method name
     * @param \array  $arguments Method parameters
     *
     * @throws SystemInfos\Exception\BadMethodCall
     *
     * @return \string
     */
    public static function __callStatic($name, array $arguments)
    {
        if (!defined('static::' . $name)) {
            throw new SystemInfos\Exception\BadMethodCall('Command not found!');
        }

        $result = self::_execute(constant('static::' . $name));

        if (in_array('serialize', $arguments)) {
            return serialize($result);
        } else {
            return implode($result, '');
        }

    }


    /**
     * Excute a command with proc_open
     *
     * @param \string $cmd Command to execute
     *
     * @throws SystemInfos\Exception\CommandFailed
     *
     * @return \array Command result
     */
    private static function _execute($cmd)
    {
        $resultValue    = array();
        $descriptorspec = array(
                           0 => array(
                                 'pipe',
                                 'r',
                                ),
                           1 => array(
                                 'pipe',
                                 'w',
                                ),
                           2 => array(
                                 'pipe',
                                 'w',
                                ),
                          );

        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            fclose($pipes[0]);

            while (!feof($pipes[1])) {
                $resultValue[] = fgets($pipes[1], 1024);
            }

            fclose($pipes[1]);
            $returnValue = proc_close($process);

            if ($returnValue != 0) {
                throw new SystemInfos\Exception\CommandFailed(
                    'Command `' . $cmd . '` failed!'
                );
            }
        }

        return $resultValue;

    }


}
