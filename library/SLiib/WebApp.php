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
 * @category SLiib
 * @package  SLiib\WebApp
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\WebApp
 *
 * @package SLiib\WebApp
 */
class WebApp
{

    /**
     * Instance
     * @var \SLiib\WebApp
     */
    private static $instance = null;

    /**
     * Application namespace
     * @var string
     */
    private $appNamespace;

    /**
     * Application path
     * @var string
     */
    private $appPath;

    /**
     * Application bootstrap
     * @var \SLiib\Bootstrap
     */
    private $bootstrap;

    /**
     * Application Init
     *
     * @param string $appNamespace Application namespace
     * @param string $appPath      Application path
     *
     * @return \SLiib\WebApp
     */
    public static function init($appNamespace, $appPath)
    {
        if (!is_null(static::$instance)) {
            return static::$instance;
        }

        Autoloader::init(array('SLiib' => 'SLiib'));

        static::$instance = new self($appNamespace, $appPath);
        return static::$instance;
    }

    /**
     * Run application
     *
     * @return void
     */
    public function run()
    {
        $namespaces = array_merge(
            array($this->appNamespace => $this->appPath),
            $this->bootstrap->getNamespaces()
        );

        Autoloader::init($namespaces, $this->bootstrap->getSections());
        $this->bootstrap->run();
    }

    /**
     * Get application instance
     *
     * @throws \SLiib\WebApp\Exception
     *
     * @return \SLiib\WebApp
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            throw new WebApp\Exception('Application not initialized.');
        }

        return static::$instance;
    }

    /**
     * Get application view path
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->bootstrap->getViewPath();
    }

    /**
     * Constructor, use static method `init` to get an instance
     *
     * @param string $appNamespace Application namespace
     * @param string $appPath      Application path
     *
     * @throws \SLiib\WebApp\Exception
     *
     * @return void
     */
    private function __construct($appNamespace, $appPath)
    {
        $this->appNamespace = $appNamespace;
        $this->appPath      = $appPath;

        $bootstrapPath = $this->appPath . '/Bootstrap.php';
        if (!file_exists($bootstrapPath)) {
            throw new WebApp\Exception('Error boostraping!');
        }

        $bsClass = '\\' . $this->appNamespace . '\\Bootstrap';
        if (!class_exists($bsClass)) {
            include $bootstrapPath;
        }

        $this->bootstrap = new $bsClass($this->appNamespace);
    }
}

