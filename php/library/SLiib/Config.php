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
 * SLiib_Config
 * 
 * @package Config
 */
abstract class SLiib_Config
{

  /**
   * Tableau des différentes directives que contient le fichier
   * 
   * @var array
   */
  protected $_directives = array();

  /**
   * Fichier de configuration à manipuler
   * 
   * @var string
   */
  protected $_configFile;


  /**
   * Constructeur. Charge le fichier de configuration passé en paramètre
   * 
   * @param string $file Fichier à charger
   * 
   * @throws SLiib_Config_Exception
   * 
   * @return void
   */
  public function __construct($file)
  {
    $this->_configFile = $file;

    if (!file_exists($file)) {
      throw new SLiib_Config_Exception('File ' . $file . ' not found');
    }

  }


  /**
   * Récupère la valeur d'une directive.
   * 
   * @param string           $dirName Nom de la directive à récupérer
   * @param string[optional] $block   Block dans lequel se trouve la directive.
   * 
   * @throws SLiib_Config_Exception
   * 
   * @return string Valeur de la directive.
   */
  public function getValue($dirName, $block=null)
  {
    if (is_null($block)) {
      if (key_exists($dirName, $this->_directives))
        return $this->_directives[$dirName];
      else
        throw new SLiib_Config_Exception(
            'Directive {' . $dirName . '} not found'
        );
    } else {
      if (key_exists($block, $this->_directives)
        && is_array($this->_directives[$block])) {
        if (key_exists($dirName, $this->_directives[$block]))
          return $this->_directives[$block][$dirName];
        else
          throw new SLiib_Config_Exception(
              'Directive {' . $dirName . '} not found on block [' . $block . ']'
          );
      } else {
        throw new SLiib_Config_Exception('Block [' . $block . '] not found');
      }
    }

  }


  /**
   * Définit une valeur pour une directive du fichier de configuration. Si un
   * block est précisé, alors la méthode définira la directive contenue dans
   * le block.
   * 
   * @param string           $dirName  Nom de la directive à modifier
   * @param string           $dirValue Valeur à affecter à la directive
   * @param string[optional] $block    Nom du block conteneur
   * 
   * @throws SLiib_Config_Exception
   * 
   * @return void
   */
  public function setDirective($dirName, $dirValue, $block=null)
  {
    if (is_null($block)) {
      if (key_exists($dirName, $this->_directives)
      && !is_array($this->_directives[$dirName])) {
        $this->_directives[$dirName] = $dirValue;
      } else {
        throw new SLiib_Config_Exception(
            'Directive {' . $dirName . '} does not exist (maybe a block..)'
        );
      }
    } else {
      if (key_exists($block, $this->_directives)
      && is_array($this->_directives[$block])) {
        if (key_exists($dirName, $this->_directives[$block]))
          $this->_directives[$block][$dirName] = $dirValue;
        else
          throw new SLiib_Config_Exception(
              'Directive {' . $dirName . '} not found on block [' . $block . ']'
          );
      } else {
        throw new SLiib_Config_Exception('Block [' . $block . '] not found');
      }
    }

  }


  /**
   * Supprime les espaces indésirables
   * 
   * @param string $string Chaine de caractères à nettoyer.
   * 
   * @return string La chaine nettoyée
   */
  protected function _cleanString($string)
  {
    //Delete tabulation
    $count = 1;
    while ($count != 0)
      $string = str_replace("\t", ' ', $string, $count);

    //Delete double space into the string
    $count = 1;
    while ($count != 0)
      $string = str_replace('  ', ' ', $string, $count);

    //Delete space around the string
    $string = trim($string);

    return $string;

  }


}
