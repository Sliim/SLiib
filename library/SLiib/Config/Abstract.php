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
        if (!file_exists($file)) {
            throw new SLiib_Config_Exception('File ' . $file . ' not found');
        }

        $this->_config     = new SLiib_Config();
        $this->_configFile = $file;
        $this->_parseFile();

    }


    /**
     * Récupère l'ensemble de la configuration.
     *
     * @return SLiib_Config
     */
    public function getConfig()
    {
        return $this->_config;

    }


    /**
     * Parse le fichier de configuration
     *
     * @return void
     */
    abstract protected function _parseFile();


}
