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
 * @package  SLiib_Bootstrap
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Bootstrap
 *
 * @package SLiib_Bootstrap
 */
abstract class SLiib_Bootstrap
{

    /**
     * Namespace de l'application
     * @var string $_appNamespace
     */
    protected $_appNamespace;

    /**
     * Namespaces to include
     * @var array $_namespaces
     */
    private $_namespaces = array();

    /**
     * Sections to include
     * @var array $_sections
     */
    private $_sections = array();


    /**
     * Initialisation du bootstrap
     *
     * @param string $appNamespace Namespace de l'application
     *
     * @return void
     */
    public function __construct($appNamespace)
    {
        $this->_appNamespace = $appNamespace;
        $this->init();

    }


    /**
     * Bootstrap init before running
     *
     * @return void
     */
    abstract public function init();


    /**
     * Démarrage du bootstrap
     *
     * @return void
     */
    public function run()
    {
        $this->_setEnvironment();

        SLiib_Dispatcher::init($this->_appNamespace);
        SLiib_Dispatcher::dispatch();

    }


    /**
     * Set namespaces
     *
     * @param array $namespaces Namespaces to set
     *
     * @return void
     */
    public function setNamespaces(array $namespaces)
    {
        $this->_namespaces = $namespaces;

    }


    /**
     * Set sections
     *
     * @param array $sections Sections to set
     *
     * @return void
     */
    public function setSections(array $sections)
    {
        $this->_sections = $sections;

    }


    /**
     * Get namespaces
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->_namespaces;

    }


    /**
     * Get sections
     *
     * @return array
     */
    public function getSections()
    {
        return $this->_sections;

    }


    /**
     * Initialise l'environnement de l'application
     *
     * @return void
     */
    protected static function _setEnvironment()
    {
        SLiib_HTTP_Request::init();
        //TODO SLiib_HTTP_Session

    }


}
