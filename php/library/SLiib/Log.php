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
 * @version  Release: 0.1.5
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Log
 * 
 * @package Log
 */
class SLiib_Log
{
  /**
   * Constantes de format utilisé pour les logs
   */
  const DATE_FORMAT = 'Y-m-d';
  const TIME_FORMAT = 'h:i:s';

  /**
   * Constantes de type d'erreur
   */
  const TYPE_DEBUG  = 'DEBUG';
  const TYPE_INFO   = 'INFO';
  const TYPE_NOTICE = 'NOTICE';
  const TYPE_WARN   = 'WARN';
  const TYPE_ERROR  = 'ERROR';
  const TYPE_CRIT   = 'CRIT';

  /**
   * Nom/Chemin du fichier de log.
   * 
   * @var string $_fileOutput
   */
  private $_fileOutput = null;

  /**
   * Format des logs
   * 
   * @var string $format
   */
  private $_format = '[%T][%d %t]%m';


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
    if (!file_exists($fileOutput))
      echo 'Creating file ' . $fileOutput . '..' . PHP_EOL;

    $opt = 'w+b';
    if ($add)
      $opt = 'a+b';

    $this->_fileOutput = @fopen($fileOutput, $opt);

    if (!$this->_fileOutput)
      throw new SLiib_Log_Exception('Cannot open file ' . $this->_fileOutput);

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
   * @return void
   */
  public function write($string, $type, $echo=false)
  {
    $log = $this->_genLog($string, $type);
    if (!fwrite($this->_fileOutput, $log . PHP_EOL)) {
      $error = error_get_last();
      throw new SLiib_Log_Exception(
          'Error ' . $error['type'] . '. ' . $error['file'] .
          ' line ' . $error['line'] . ':' . $error['message']
      );
    }

    if ($echo)
      echo $string . PHP_EOL;

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
    if (array_key_exists('REMOTE_ADDR', $_SERVER))
      return $_SERVER['REMOTE_ADDR'];
    return false;

  }


  /**
   * Callback pour le format User-Agent (%U)
   * 
   * @return string User-Agent du client ou false si inexistant
   */
  private static function _getUserAgent()
  {
    if (array_key_exists('HTTP_USER_AGENT', $_SERVER))
      return $_SERVER['HTTP_USER_AGENT'];
    return false;

  }


}
