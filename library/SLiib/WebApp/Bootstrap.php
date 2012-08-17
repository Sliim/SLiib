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
     * Application namespace
     * @var string
     */
    private $appNamespace;

    /**
     * Web Application namespaces
     * @var array
     */
    private $namespaces = array();

    /**
     * Web Application sections
     * @var array
     */
    private $sections = array();

    /**
     * Application view path
     * @var string
     */
    private $viewPath = null;

    /**
     * Security checkers
     * @var array
     */
    private $securityCheckers = array();

    /**
     * Bootstrap init
     *
     * @param string $appNamespace Namespace de l'application
     *
     * @return void
     */
    final public function __construct($appNamespace)
    {
        $this->appNamespace = $appNamespace;
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
    final public function run()
    {
        try {
            $this->setEnvironment();

            Dispatcher::init($this->appNamespace);
            Dispatcher::dispatch();
        } catch (\SLiib\Exception $e) {
            $this->exceptionHandler($e);
        }
    }

    /**
     * Get namespaces
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Get application view path
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Set namespaces
     *
     * @param array $namespaces Namespaces to set
     *
     * @return void
     */
    protected function setNamespaces(array $namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * Set sections
     *
     * @param array $sections Sections to set
     *
     * @return void
     */
    protected function setSections(array $sections)
    {
        $this->sections = $sections;
    }

    /**
     * Application environment init
     *
     * @return void
     */
    protected function setEnvironment()
    {
        Request::init();
        Session::init();
        Security::check($this->securityCheckers);
    }

    /**
     * Set application view path
     *
     * @param string $path View path
     *
     * @throws \SLiib\WebApp\Exception
     *
     * @return void
     */
    protected function setViewPath($path)
    {
        if (!file_exists($path)) {
            throw new Exception('Directory ' . $path . ' not found.');
        }

        $this->viewPath = $path;
    }

    /**
     * Define security checker
     *
     * @param array $checkers Checkers to set
     *
     * @return void
     */
    protected function setSecurityCheckers(array $checkers)
    {
        $this->securityCheckers = $checkers;
    }

    /**
     * Exception Handler
     *
     * @param \Exception $e The exception object
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function exceptionHandler(\Exception $e)
    {
        throw $e;
    }
}

