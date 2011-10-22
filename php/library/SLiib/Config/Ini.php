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
 * @version    Release: 0.2
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
   * Lit le fichier de configuration
   *
   * @see SLiib_Config
   *
   * @throws SLiib_Config_Exception
   * @throws SLiib_Config_Exception_SyntaxError
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
    $count = 0;
    while ($line = fgets($fp, 256)) {
      $count++;
      $line = SLiib_String::clean($line);
      if (!empty($line)) {
        switch ($line[0]) {
          case ';':
              break;
          case '[':
            if (preg_match('/ : /', $line)) {
              $seg    = explode(' : ', $line);
              $block  = SLiib_String::clean(str_replace('[', '', $seg[0]));
              $parent = SLiib_String::clean(str_replace(']', '', $seg[1]));

              if (!isset($this->_config->$parent)) {
                throw new SLiib_Config_Exception_SyntaxError(
                    '`' . $parent . '` undefined in `' . $this->_configFile . '` at line ' . $count
                );
              }

              $this->_config->$block = clone $this->_config->$parent;
            } else {
              $block = str_replace(array('[', ']'), '', $line);

              $this->_config->$block = new stdClass;
            }
              break;
          default:
            $data      = explode('=', $line);
            $directive = SLiib_String::clean($data[0]);
            $value     = SLiib_String::clean($data[1]);

            if (!$block) {
              $this->_config->$directive = $value;
            } else {
              $this->_config->$block->$directive = $value;
            }
              break;
        }
      }
    }

    fclose($fp);

  }


}
