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
 * @package  SLiib\Log
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\Log
 *
 * @package SLiib\Log
 */
class Log
{
    /**
     * Date and time format
     */
    const DATE_FORMAT = 'Y-m-d';
    const TIME_FORMAT = 'h:i:s';

    /**
     * Debug type
     */
    const DEBUG = 'DEBUG';
    const INFO  = 'INFO';
    const WARN  = 'WARN';
    const ERROR = 'ERROR';
    const CRIT  = 'CRIT';

    /**
     * Log file path
     * @var string
     */
    private $_fileOutput = NULL;

    /**
     * Log format
     * @var string
     */
    private $_format = '[%d %t] [%T] - %m';


    /**
     * Constructor, init file descriptor.
     *
     * @param string            $fileOutput File path
     * @param boolean[optional] $add        True add in file.
     *
     * @throws Log\Exception
     *
     * @return void
     */
    public function __construct($fileOutput, $add=FALSE)
    {
        $opt = 'w+b';
        if ($add) {
            $opt = 'a+b';
        }

        $this->_fileOutput = @fopen($fileOutput, $opt);

        if (!$this->_fileOutput) {
            throw new Log\Exception('Cannot open file ' . $fileOutput);
        }

    }


    /**
     * Destructeur : Close file descriptor.
     *
     * @return void
     */
    public function __destruct()
    {
        fclose($this->_fileOutput);

    }


    /**
     * Write in log file
     *
     * @param string            $string String to write
     * @param string            $type   Log type
     * @param boolean[optional] $echo   Print on stdout
     *
     * @return \SLiib\Log
     */
    public function write($string, $type=self::INFO, $echo=FALSE)
    {
        $log = $this->_genLog($string, $type);
        fwrite($this->_fileOutput, $log . PHP_EOL);

        if ($echo) {
            $this->_printStdout($string, $type);
        }

        return $this;

    }


    /**
     * Dump a variable and save it in log file
     *
     * @param mixed             $var  Variable to debug
     * @param boolean[optional] $echo Print on stdout
     *
     * @return \SLiib\Log
     */
    public function debug($var, $echo=FALSE)
    {
        $dump = Debug::dump($var, FALSE);

        return $this->write($dump, self::DEBUG, $echo);

    }


    /**
     * Write information log
     *
     * @param string            $string String to write
     * @param boolean[optional] $echo   Print on stdout
     *
     * @return \SLiib\Log
     */
    public function info($string, $echo=FALSE)
    {
        return $this->write($string, self::INFO, $echo);

    }


    /**
     * Write warning log
     *
     * @param string            $string String to write
     * @param boolean[optional] $echo   Print on stdout
     *
     * @return \SLiib\Log
     */
    public function warn($string, $echo=FALSE)
    {
        return $this->write($string, self::WARN, $echo);

    }


    /**
     * Write error log
     *
     * @param string            $string String to write
     * @param boolean[optional] $echo   Print on stdout
     *
     * @return \SLiib\Log
     */
    public function error($string, $echo=FALSE)
    {
        return $this->write($string, self::ERROR, $echo);

    }


    /**
     * Write critical log
     *
     * @param string            $string String to write
     * @param boolean[optional] $echo   Print on stdout
     *
     * @return \SLiib\Log
     */
    public function crit($string, $echo=FALSE)
    {
        return $this->write($string, self::CRIT, $echo);

    }


    /**
     * Set log format
     * Available elements :
     * -date : %d
     * -time : %t
     * -ip : %@
     * -user-agent : %U
     * -message : %m
     * -type : %T
     *
     * @param string $format Format to set.
     *
     * @return void
     */
    public function setFormat($format)
    {
        $this->_format = $format;

    }


    /**
     * Get log format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;

    }


    /**
     * Generate log string
     *
     * @param string $message String input
     * @param string $type    Log type (error, crit, warn, info..)
     *
     * @return string
     */
    private function _genLog($message, $type)
    {
        $log = $this->getFormat();
        $log = preg_replace_callback('/%d/', 'self::_getDate', $log);
        $log = preg_replace_callback('/%t/', 'self::_getTime', $log);
        $log = preg_replace_callback('/%@/', 'self::_getIp', $log);
        $log = preg_replace_callback('/%U/', 'self::_getUserAgent', $log);
        $log = preg_replace('/%T/', $type, $log);
        $log = preg_replace('/%m/', $message, $log);

        return $log;

    }


    /**
     * Get date callback (%d)
     *
     * @return string
     */
    private static function _getDate()
    {
        return date(self::DATE_FORMAT);

    }


    /**
     * Get time callback (%t)
     *
     * @return string
     */
    private static function _getTime()
    {
        return date(self::TIME_FORMAT);

    }


    /**
     * Get IP callback (%@)
     *
     * @return string
     */
    private static function _getIp()
    {
        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return NULL;

    }


    /**
     * Get user agent callback (%U)
     *
     * @return string
     */
    private static function _getUserAgent()
    {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            return $_SERVER['HTTP_USER_AGENT'];
        }

        return NULL;

    }


    /**
     * Write string on stdout
     *
     * @param string $string String to print
     * @param string $type   Log type
     *
     * @return void
     */
    private function _printStdout($string, $type)
    {
        $color        = "\033[0m";
        $defaultColor = $color;

        switch ($type) {
            case self::DEBUG:
                $color = "\033[34m";
                break;
            case self::WARN:
                $color = "\033[33m";
                break;
            case self::ERROR:
            case self::CRIT:
                $color = "\033[31m";
                break;
            default:
                //No color
                break;
        }

        echo $color . $string . $defaultColor . PHP_EOL;

    }


}
