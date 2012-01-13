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
 * PHP Version 5.3
 *
 * @category   SLiib
 * @package    SLiib\WebApp
 * @subpackage Bootstrap
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp;

/**
 * \SLiib\WebApp\Bootstrap
 *
 * @package    SLiib\WebApp
 * @subpackage Bootstrap
 */
abstract class Bootstrap
{

    /**
     * Namespace de l'application
     * @var \string
     */
    private $_appNamespace;

    /**
     * Web Application namespaces
     * @var \array
     */
    private $_namespaces = array();

    /**
     * Web Application sections
     * @var \array
     */
    private $_sections = array();

    /**
     * Application view path
     * @var \string
     */
    private $_viewPath = NULL;

    /**
     * Security checkers
     * @var \array
     */
    private $_securityCheckers = array();


    /**
     * Initialisation du bootstrap
     *
     * @param \string $appNamespace Namespace de l'application
     *
     * @return \void
     */
    public final function __construct($appNamespace)
    {
        $this->_appNamespace = $appNamespace;
        $this->init();

    }


    /**
     * Bootstrap init before running
     *
     * @return \void
     */
    abstract public function init();


    /**
     * Démarrage du bootstrap
     *
     * @return \void
     */
    public final function run()
    {
        try {
            $this->_setEnvironment();

            Dispatcher::init($this->_appNamespace);
            Dispatcher::dispatch();
        } catch (\SLiib\Exception $e) {
            $this->_exceptionHandler($e);
        }

    }


    /**
     * Get namespaces
     *
     * @return \array
     */
    public function getNamespaces()
    {
        return $this->_namespaces;

    }


    /**
     * Get sections
     *
     * @return \array
     */
    public function getSections()
    {
        return $this->_sections;

    }


    /**
     * Get application view path
     *
     * @return \string
     */
    public function getViewPath()
    {
        return $this->_viewPath;

    }


    /**
     * Set namespaces
     *
     * @param \array $namespaces Namespaces to set
     *
     * @return \void
     */
    protected function _setNamespaces(array $namespaces)
    {
        $this->_namespaces = $namespaces;

    }


    /**
     * Set sections
     *
     * @param \array $sections Sections to set
     *
     * @return \void
     */
    protected function _setSections(array $sections)
    {
        $this->_sections = $sections;

    }


    /**
     * Initialise l'environnement de l'application
     *
     * @return \void
     */
    protected function _setEnvironment()
    {
        Request::init();
        Session::init();
        Security::check($this->_securityCheckers);

    }


    /**
     * Set application view path
     *
     * @param \string $path View path
     *
     * @throws Exception
     *
     * @return \void
     */
    protected function _setViewPath($path)
    {
        if (!file_exists($path)) {
            throw new Exception('Directory ' . $path . ' not found.');
        }

        $this->_viewPath = $path;

    }


    /**
     * Define security checker
     *
     * @param \array $checkers Checkers to set
     *
     * @return \void
     */
    protected function _setSecurityCheckers(array $checkers)
    {
        $this->_securityCheckers = $checkers;

    }


    /**
     * Exception Handler
     *
     * @param \Exception $e The exception object
     *
     * @throws \Exception
     *
     * @return \void
     */
    protected function _exceptionHandler(\Exception $e)
    {
        throw $e;

    }


}
