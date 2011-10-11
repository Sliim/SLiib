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
 * @package  SLiib_Cli
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.1.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Cli
 * 
 * @package SLiib_Cli
 */
abstract class SLiib_Cli
{

  /**
   * Version du script
   *
   * @var $_version
   */
  protected $_version = 0;

  /**
   * Description du script
   *
   * @var $_desc
   */
  protected $_desc = 'Pas de description.';

  /**
   * Auteur du script
   *
   * @var $_author
   */
  protected $_author = 'Inconnu';

  /**
   * Options par défaut possible du script
   *
   * @var $_defaultOpt
   */
  protected $_defaultOpt = array(
                            'V' => array(
                                    'desc' => 'Affiche la version du script.',
                                    'func' => '_version',
                                   ),
                            'h' => array(
                                    'desc' => 'Affiche l\'aide.',
                                    'func' => '_help',
                                   )
                           );

  /**
   * Options possible du script
   *
   * @var $_options
   */
  protected $_options = null;

  /**
   * Chaine utilisée pour etre passée à getopt.
   *
   * @var $_param
   */
  protected $_params;


  /**
   * Constructeur
   *
   * @param array[optional] $options Options possible pour le script
   *
   * @return void
   */
  public function __construct ($options=null)
  {
    if (!is_null($options)) {
      $this->_options = array_merge($this->_defaultOpt, $options);

      //Initialisation des paramètres passés au script
      $params = '';
      foreach ($this->_options as $option => $desc) {
        if (!key_exists('desc', $desc)) {
          $this->_options[$option]['desc'] = 'Pas de description.';
        }

        $params .= $option;

        $this->_options[str_replace(':', '', $option, $count)] =
          $this->_options[$option];
        if ($count > 0)
          unset($this->_options[$option]);
      }

      //Récupération des paramètres passés au script
      $this->_params = getopt($params);

      //Exécution de la fonction demandée s'il y a.
      foreach ($this->_params as $param => $value) {
        if (key_exists('func', $this->_options[$param])) {
          $func = $this->_options[$param]['func'];
          if (function_exists($this->$func($value)))
            $this->$func($value);
        }
      }
    }

  }


  /**
   * Affiche l'aide du script.
   * (Méthode utilisée par le paramètre -h par défaut)
   *
   * @return void
   */
  protected function _help ()
  {
    echo $this->_desc . PHP_EOL . PHP_EOL;
    if (!is_null($this->_options)) {
      foreach ($this->_options as $opt => $optDesc)
        echo "\t-" . $opt . "\t" . $optDesc['desc'] . PHP_EOL;
    }

    echo PHP_EOL;
    echo 'Auteur : ' . $this->_author . PHP_EOL;
    echo 'Version : ' . $this->_version . PHP_EOL;
    exit();

  }


  /**
   * Affiche la version du script.
   * (Méthode utilisée par le paramètre -V par défaut)
   *
   * @return void
   */
  protected function _version ()
  {
    echo $this->_version . PHP_EOL;
    exit();

  }


}
