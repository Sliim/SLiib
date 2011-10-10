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
 * @version  Release: 0.1
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Config_Ini
 * 
 * @package    Config
 * @subpackage Ini
 */
class SLiib_Config_Ini extends SLiib_Config
{


  /**
   * Constructeur. Charge le fichier ini passé en paramètre
   * 
   * @param string $file Fichier à charger
   * 
   * @return void
   */
  public function __construct($file)
  {
    parent::__construct($file);

    $this->_readIni();

  }


  /**
   * Récupère l'ensemble de la configuration.
   * 
   * @return array Tableau comportant l'ensemble de la configuration.
   */
  public function getAll()
  {
    return $this->_directives;

  }


  /**
   * Réecrit le fichier de configuration
   * 
   * @throws SLiib_Config_Exception
   * 
   * @return void
   */
  public function rewriteConfig()
  {
    $newContent = '';
    foreach ($this->_directives as $key => $value) {
      if (is_array($value)) {
        $newContent .= '[' . $key . ']' . "\r\n";
        foreach ($value as $dirName => $dirValue) {
          $newContent .= $dirName . ' = ' . $dirValue . "\r\n";
        }
      } else {
        $newContent .= $key . ' = ' . $value . "\r\n";
      }
    }

    $fp = fopen($this->_configFile, 'w');
    if (!$fp)
      throw new SLiib_Config_Exception(
          'Cannot open configuration file ' .
          $this->_configFile . ' in write mode.'
      );

    if (!fwrite($fp, $newContent)) {
      fclose($fp);

      throw new SLiib_Config_Exception(
          'Cannot write in configuration file ' . $this->_configFile
      );
    }

    fclose($fp);

  }


  /**
   * Enregistre les données du fichier .ini dans le tableau $_directives
   * 
   * @throws SLiib_Config_Exception
   * 
   * @return void
   */
  private function _readIni()
  {
    $fp = fopen($this->_configFile, 'r');
    if (!$fp)
      throw new SLiib_Config_Exception(
          'Opening file ' . $this->_configFile . ' failed'
      );

    //Block courant du fichier
    $block = '';

    while ($line = fgets($fp, 256)) {
      $line = $this->_cleanString($line);
      if (!empty($line)) {
        switch ($line[0]) {
          case '#':
              break;
          case '[':
            $block = str_replace(array('[', ']'), '', $line);
            if (key_exists($block, $this->_directives)) {
              fclose($fp);

              throw new SLiib_Config_Exception(
                  'Ini error : Block name [' . $block .
                  '] is already used in the ini file'
              );
            }

            $this->_directives[$block] = array();
              break;
          default:
            $data      = explode('=', $line);
            $directive = $this->_cleanString($data[0]);
            $value     = $this->_cleanString($data[1]);

            if (empty($block)) {
              if (key_exists($directive, $this->_directives)) {
                fclose($fp);

                throw new SLiib_Config_Exception(
                    'Ini error : Directive name {' .
                    $directive . '} is already used in the ini file'
                );
              }

              $this->_directives[$directive] = $value;
            } else {
              if (key_exists($directive, $this->_directives[$block])) {
                fclose($fp);

                throw new SLiib_Config_Exception(
                    'Ini error : Directive name is already used' .
                    ' in the ini file [directive => ' . $directive .
                    ', block => ' . $block . ']'
                );
              }

              $this->_directives[$block][$directive] = $value;
            }
              break;
        }
      }
    }

    fclose($fp);

  }


}
