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
 * @subpackage Abstract
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Config_Abstract
 *
 * @package    SLiib_Config
 * @subpackage Abstract
 */
abstract class SLiib_Config_Abstract
{

    /**
     * Objet de la configuration
     * @var SLiib_Config
     */
    protected $_config = NULL;

    /**
     * Fichier de configuration à utiliser
     * @var string
     */
    protected $_configFile;

    /**
     * Configuration environment
     * @var string
     */
    protected $_environment = NULL;


    /**
     * Constructeur. Charge le fichier de configuration passé en paramètre
     *
     * @param string           $file Fichier à charger
     * @param string[optional] $env  Config environment
     *
     * @throws SLiib_Config_Exception
     *
     * @return void
     */
    public function __construct($file, $env=NULL)
    {
        if (!file_exists($file)) {
            throw new SLiib_Config_Exception('File ' . $file . ' not found');
        }

        $this->_config     = new SLiib_Config();
        $this->_configFile = $file;
        $this->_parseFile();

        if (!is_null($env)) {
            $this->setEnvironment($env);
        }

    }


    /**
     * Récupère l'ensemble de la configuration.
     *
     * @return SLiib_Config
     */
    public function getConfig()
    {
        if (!is_null($this->_environment)) {
            return $this->_config->{$this->_environment};
        }

        return $this->_config;

    }


    /**
     * Environment setter
     *
     * @param string $env Environment name to set
     *
     * @throws SLiib_Config_Exception_UndefinedProperty
     *
     * @return void
     */
    public function setEnvironment($env)
    {
        if (isset($this->_config->$env)) {
            $this->_environment = $env;
        } else {
            throw new SLiib_Config_Exception_UndefinedProperty(
                'Environment specified `' . $env . '` not found in configuration file.'
            );
        }

    }


    /**
     * Environment getter
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->_environment;

    }


    /**
     * Parse le fichier de configuration
     *
     * @return void
     */
    abstract protected function _parseFile();


}
