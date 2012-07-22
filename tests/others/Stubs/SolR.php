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
 * PHP version 5.3
 *
 * @category   SLiib
 * @package    Tests
 * @subpackage Stubs
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace Stubs;

/**
 * SolR stubs manager
 *
 * @package    Tests
 * @subpackage Stubs
 */
class SolR
{
    /**
     * Instance processus id
     * @var int
     */
    private static $_pid = null;

    /**
     * Check if SolR stubs is installed
     *
     * @return boolean
     */
    public static function installed()
    {
        return (file_exists(dirname(__FILE__) . '/SolR/start.jar')) ? true : false;
    }

    /**
     * Check if JVM is available
     *
     * @return boolean
     */
    public static function jvmAvailable()
    {
        exec('java -version 2> /dev/null', $output, $returnCode);
        return ($returnCode === 0) ? true : false;
    }

    /**
     * Check if SolR stubs is started
     *
     * @return boolean
     */
    public static function started()
    {
        return (is_null(static::$_pid)) ? false : true;
    }

    /**
     * Start instance and return his pid
     *
     * @return mixed If already started : TRUE, if start successfuly : his pid else FALSE
     */
    public static function start()
    {
        if (self::started()) {
            return true;
        }

        $command  = 'cd ' . dirname(__FILE__) . '/SolR/ && ';
        $command .= 'java -jar start.jar > /dev/null 2>/dev/null & ';
        $command .= 'echo $!';

        exec($command, $output, $returnCode);
        static::$_pid = $output[0];

        return ($returnCode === 0) ? true : false;
    }

    /**
     * Stop instance
     *
     * @return boolean
     */
    public static function stop()
    {
        if (!is_null(static::$_pid)) {
            if (posix_kill(static::$_pid, SIGKILL)) {
                static::$_pid = null;
            } else {
                return false;
            }
        }

        return true;
    }
}

