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
 * @package    SLiib_Abstract
 * @subpackage Cli
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Abstract_Cli
 *
 * @package    SLiib_Abstract
 * @subpackage Cli
 */
abstract class SLiib_Abstract_Cli
{

    /**
     * Script description
     * @var string
     */
    const NO_DESCRIPTION_LABEL = 'No description';

    /**
     * Script author label when unknown
     * @var string
     */
    const AUTHOR_UNKNOWN_LABEL = 'Inconnu';

    /**
     * Script author label
     * @var string
     */
    const AUTHOR_LABEL = 'Auteur';

    /**
     * Script version label
     * @var string
     */
    const VERSION_LABEL = 'Version';

    /**
     * Help menu text
     * @var string
     */
    const SHOW_HELP_LABEL = 'Affiche l\'aide.';

    /**
     * Version menu text
     * @var string
     */
    const SHOW_VERSION_LABEL = 'Affiche la version du script.';

    /**
     * Version du script
     * @var float
     */
    protected $_version = 0;

    /**
     * Description du script
     * @var string
     */
    protected $_desc = self::NO_DESCRIPTION_LABEL;

    /**
     * Auteur du script
     * @var string
     */
    protected $_author = self::AUTHOR_UNKNOWN_LABEL;

    /**
     * Options par défaut possible du script
     * @var array
     */
    protected $_defaultOpt = array(
                              'V' => array(
                                      'desc' => self::SHOW_VERSION_LABEL,
                                      'func' => '_version',
                                     ),
                              'h' => array(
                                      'desc' => self::SHOW_HELP_LABEL,
                                      'func' => '_help',
                                     )
                             );

    /**
     * Options possible du script
     * @var array
     */
    protected $_options = NULL;

    /**
     * Chaine utilisée pour etre passée à getopt.
     * @var string
     */
    protected $_params;


    /**
     * Constructeur
     *
     * @param array[optional] $options Options possible pour le script
     *
     * @return void
     */
    public function __construct ($options=NULL)
    {
        if (!is_null($options)) {
            $this->_options = array_merge($this->_defaultOpt, $options);

            //Initialisation des paramètres passés au script
            $params = '';
            foreach ($this->_options as $option => $desc) {
                if (!array_key_exists('desc', $desc)) {
                    $this->_options[$option]['desc'] = self::NO_DESCRIPTION;
                }

                $params .= $option;

                $this->_options[str_replace(':', '', $option, $count)] =
                  $this->_options[$option];
                if ($count > 0) {
                    unset($this->_options[$option]);
                }
            }

            //Récupération des paramètres passés au script
            $this->_params = getopt($params);

            //Exécution de la fonction demandée s'il y a.
            foreach ($this->_params as $param => $value) {
                if (array_key_exists('func', $this->_options[$param])) {
                    $func = $this->_options[$param]['func'];
                    if (function_exists($this->$func($value))) {
                        $this->$func($value);
                    }
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
            foreach ($this->_options as $opt => $optDesc) {
                echo "\t-" . $opt . "\t" . $optDesc['desc'] . PHP_EOL;
            }
        }

        echo PHP_EOL;
        echo self::AUTHOR_LABEL . ' : ' . $this->_author . PHP_EOL;
        echo self::VERSION_LABEL . ' : ' . $this->_version . PHP_EOL;
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
