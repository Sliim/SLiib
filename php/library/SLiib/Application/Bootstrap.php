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
 * @package    SLiib_Application
 * @subpackage SLiib_Application_Bootstrap
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Bootstrap
 *
 * @package    SLiib_Application
 * @subpackage SLiib_Application_Bootstrap
 */
abstract class SLiib_Application_Bootstrap
{

    /**
     * Namespace de l'application
     * @var string $_appNamespace
     */
    private $_appNamespace;

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
     * Application view path
     * @var string $_viewPath
     */
    private $_viewPath = null;


    /**
     * Initialisation du bootstrap
     *
     * @param string $appNamespace Namespace de l'application
     *
     * @return void
     */
    public final function __construct($appNamespace)
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
    public final function run()
    {
        try {
            $this->_setEnvironment();

            SLiib_Application_Dispatcher::init($this->_appNamespace);
            SLiib_Application_Dispatcher::dispatch();
        } catch (SLiib_Exception $e) {
            $this->_exceptionHandler($e);
        }

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
     * Get application view path
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->_viewPath;

    }


    /**
     * Set namespaces
     *
     * @param array $namespaces Namespaces to set
     *
     * @return void
     */
    protected function _setNamespaces(array $namespaces)
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
    protected function _setSections(array $sections)
    {
        $this->_sections = $sections;

    }


    /**
     * Initialise l'environnement de l'application
     *
     * @return void
     */
    protected function _setEnvironment()
    {
        SLiib_HTTP_Request::init();
        //TODO SLiib_HTTP_Session

    }


    /**
     * Set application view path
     *
     * @param string $path View path
     *
     * @throws SLiib_Application_Exception
     *
     * @return void
     */
    protected function _setViewPath($path)
    {
        if (!file_exists($path)) {
            throw new SLiib_Application_Exception('Directory ' . $path . ' not found.');
        }

        $this->_viewPath = $path;

    }


    /**
     * Exception Handler
     *
     * @param Exception $e
     *
     * @return void
     */
    protected function _exceptionHandler(Exception $e)
    {
        throw $e;

    }


}
