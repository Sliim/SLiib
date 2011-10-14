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
 * @category   SLiib
 * @package    SLiib_Config
 * @subpackage SLiib_Config_Ini
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.1.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Config_Ini
 * 
 * @package    SLiib_Config
 * @subpackage SLiib_Config_Ini
 */
class SLiib_Config_Ini extends SLiib_Config
{


  /**
   * Définit une directive de la configuration
   * 
   * @param string           $directive Nom de la directive à modifier
   * @param string           $value     Valeur à affecter à la directive
   * @param string[optional] $block     Nom du block conteneur
   * 
   * @see SLiib_Config
   * 
   * @throws SLiib_Config_Exception
   * 
   * @return void
   */
  public function setDirective($directive, $value, $block=null)
  {
    if (is_null($block)) {
      if (isset($this->_config->$directive)
      && !is_object($this->_config->$directive)) {
        $this->_config->$directive = $value;
      } else {
        throw new SLiib_Config_Exception(
            'Directive {' . $directive . '} does not exist (maybe a block..)'
        );
      }
    } else {
      if (isset($this->_config->$block)
      && is_object($this->_config->$block)) {
        if (isset($this->_config->$block->$directive))
          $this->_config->$block->$directive = $value;
        else
          throw new SLiib_Config_Exception(
              'Directive {' . $directive .
              '} not found on block [' . $block . ']'
          );
      } else {
        throw new SLiib_Config_Exception('Block [' . $block . '] not found');
      }
    }

  }


  /**
   * Enregistre la configuration
   * 
   * @see SLiib_Config
   *
   * @throws SLiib_Config_Exception
   *
   * @return void
   */
  public function saveConfig()
  {
    $newContent = '';
    foreach ($this->_config as $key => $value) {
      if (is_object($value)) {
        $newContent .= '[' . $key . ']' . "\r\n";
        foreach ($value as $directive => $value) {
          $newContent .= $directive . ' = ' . $value . "\r\n";
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
   * Lit le fichier de configuration
   * 
   * @see SLiib_Config
   * 
   * @throws SLiib_Config_Exception
   * 
   * @return void
   */
  protected function _readFile()
  {
    $fp = fopen($this->_configFile, 'r');
    if (!$fp)
      throw new SLiib_Config_Exception(
          'Opening file ' . $this->_configFile . ' failed'
      );

    //Block courant du fichier
    $block = false;

    while ($line = fgets($fp, 256)) {
      $line = SLiib_String::clean($line);
      if (!empty($line)) {
        switch ($line[0]) {
          case '#':
              break;
          case '[':
            $block = str_replace(array('[', ']'), '', $line);
            if (isset($this->_config->$block)) {
              fclose($fp);

              throw new SLiib_Config_Exception(
                  'Ini error : Block name [' . $block .
                  '] is already used in the ini file'
              );
            }

            $this->_config->$block = new stdClass;
              break;
          default:
            $data      = explode('=', $line);
            $directive = SLiib_String::clean($data[0]);
            $value     = SLiib_String::clean($data[1]);

            if (!$block) {
              if (isset($this->_config->$directive)) {
                fclose($fp);

                throw new SLiib_Config_Exception(
                    'Ini error : Directive name {' .
                    $directive . '} is already used in the ini file'
                );
              }

              $this->_config->$directive = $value;
            } else {
              if (isset($this->_config->$block->$directive)) {
                fclose($fp);

                throw new SLiib_Config_Exception(
                    'Ini error : Directive name is already used' .
                    ' in the ini file [directive => ' . $directive .
                    ', block => ' . $block . ']'
                );
              }

              $this->_config->$block->$directive = $value;
            }
              break;
        }
      }
    }

    fclose($fp);

  }


}
