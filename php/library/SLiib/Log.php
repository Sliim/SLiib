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
 * @package  SLiib_Log
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Log
 *
 * @package SLiib_Log
 */
class SLiib_Log
{
    /**
     * Constantes de format utilisé pour les logs
     */
    const DATE_FORMAT = 'Y-m-d';
    const TIME_FORMAT = 'h:i:s';

    /**
     * Constantes de type de log
     */
    const DEBUG = 'DEBUG';
    const INFO  = 'INFO';
    const WARN  = 'WARN';
    const ERROR = 'ERROR';
    const CRIT  = 'CRIT';

    /**
     * Nom/Chemin du fichier de log.
     * @var string
     */
    private $_fileOutput = null;

    /**
     * Format des logs
     * @var string
     */
    private $_format = '[%d %t] [%T] - %m';


    /**
     * Constructor, initialise le file descriptor.
     *
     * @param string         $fileOutput Nom/Chemin vers le fichier à utiliser.
     * @param bool[optional] $add        True pour ajouter à la suite du fichier.
     *
     * @throws SLiib_Log_Exception
     *
     * @return void
     */
    public function __construct($fileOutput, $add=false)
    {
        $opt = 'w+b';
        if ($add) {
            $opt = 'a+b';
        }

        $this->_fileOutput = @fopen($fileOutput, $opt);

        if (!$this->_fileOutput) {
            throw new SLiib_Log_Exception('Cannot open file ' . $this->_fileOutput);
        }

    }


    /**
     * Destructeur : ferme le fichier de log.
     *
     * @return void
     */
    public function __destruct()
    {
        fclose($this->_fileOutput);

    }


    /**
     * Écrit dans le fichier de log
     *
     * @param string         $string Chaine à écrire dans le fichier
     * @param string         $type   Type de log
     * @param bool[optional] $echo   Affiche ou non sur la sortie standard.
     *
     * @throws SLiib_Log_Exception
     *
     * @return SLiib_Log
     */
    public function log($string, $type=self::INFO, $echo=false)
    {
        $log = $this->_genLog($string, $type);
        fwrite($this->_fileOutput, $log . PHP_EOL);

        if ($echo) {
            $this->_printStdout($string, $type);
        }

        return $this;

    }


    /**
     * Ecrit un log de type DEBUG
     *
     * @param string            $string Message du log
     * @param boolean[optional] $echo   Afficher le log dans le stdout
     *
     * @return SLiib_Log
     */
    public function debug($string, $echo=false)
    {
        return $this->log($string, self::DEBUG, $echo);

    }


    /**
     * Ecrit un log de type INFO
     *
     * @param string            $string Message du log
     * @param boolean[optional] $echo   Afficher le log dans le stdout
     *
     * @return SLiib_Log
     */
    public function info($string, $echo=false)
    {
        return $this->log($string, self::INFO, $echo);

    }


    /**
     * Ecrit un log de type WARN
     *
     * @param string            $string Message du log
     * @param boolean[optional] $echo   Afficher le log dans le stdout
     *
     * @return SLiib_Log
     */
    public function warn($string, $echo=false)
    {
        return $this->log($string, self::WARN, $echo);

    }


    /**
     * Ecrit un log de type ERROR
     *
     * @param string            $string Message du log
     * @param boolean[optional] $echo   Afficher le log dans le stdout
     *
     * @return SLiib_Log
     */
    public function error($string, $echo=false)
    {
        return $this->log($string, self::ERROR, $echo);

    }


    /**
     * Ecrit un log de type CRIT
     *
     * @param string            $string Message du log
     * @param boolean[optional] $echo   Afficher le log dans le stdout
     *
     * @return SLiib_Log
     */
    public function crit($string, $echo=false)
    {
        return $this->log($string, self::CRIT, $echo);

    }


    /**
     * Définit le format utilisé pour les logs
     * Les différents éléments sont :
     * -date : %d
     * -time : %t
     * -ip : %@
     * -user-agent : %U
     * -message : %m
     * -type : %T
     *
     * @param string $format Format à définir.
     *
     * @return void
     */
    public function setFormat($format)
    {
        $this->_format = $format;

    }


    /**
     * Retourne le format actuel des logs
     *
     * @return string format actuel des logs
     */
    public function getFormat()
    {
        return $this->_format;

    }


    /**
     * Génère le log en foncton du format spécifié
     *
     * @param string $message Message à écrire
     * @param string $type    Type de log (error, crit, warn, info..)
     *
     * @return log formatté
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
     * Callback pour le format date (%d)
     *
     * @return string Date actuelle
     */
    private static function _getDate()
    {
        return date(self::DATE_FORMAT);

    }


    /**
     * Callback pour le format heure (%t)
     *
     * @return string Heure actuelle
     */
    private static function _getTime()
    {
        return date(self::TIME_FORMAT);

    }


    /**
     * Callback pour le format IP (%@)
     *
     * @return string Adresse IP du client ou false si inexistante
     */
    private static function _getIp()
    {
        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return false;

    }


    /**
     * Callback pour le format User-Agent (%U)
     *
     * @return string User-Agent du client ou false si inexistant
     */
    private static function _getUserAgent()
    {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            return $_SERVER['HTTP_USER_AGENT'];
        }

        return false;

    }


    /**
     * Ecrit le message sur le stdout
     *
     * @param string $string Chaine à afficher
     * @param string $type   Type de log
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

        $stdout = new self('php://stdout');
        $stdout->setFormat('%m');
        $stdout->log($color . $string . $defaultColor);

    }


}
