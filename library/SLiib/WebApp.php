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
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

require_once 'SLiib/Autoloader.php';

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
    private static $_instance = NULL;

    /**
     * Application namespace
     * @var \string
     */
    private $_appNamespace;

    /**
     * Application path
     * @var \string
     */
    private $_appPath;

    /**
     * Application bootstrap
     * @var \SLiib\Bootstrap
     */
    private $_bootstrap;


    /**
     * Application Init
     *
     * @param \string $appNamespace Application namespace
     * @param \string $appPath      Application path
     *
     * @return \SLiib\WebApp
     */
    public static function init($appNamespace, $appPath)
    {
        if (!is_null(static::$_instance)) {
            return static::$_instance;
        }

        Autoloader::init(array('SLiib' => 'SLiib'));

        static::$_instance = new self($appNamespace, $appPath);
        return static::$_instance;

    }


    /**
     * Run application
     *
     * @return \void
     */
    public function run()
    {
        $namespaces = array_merge(
            array($this->_appNamespace => $this->_appPath),
            $this->_bootstrap->getNamespaces()
        );

        Autoloader::init($namespaces, $this->_bootstrap->getSections());
        $this->_bootstrap->run();

    }


    /**
     * Get application instance
     *
     * @throws WebApp\Exception
     *
     * @return \SLiib\WebApp
     */
    public static function getInstance()
    {
        if (static::$_instance === NULL) {
            throw new WebApp\Exception('Application not initialized.');
        }

        return static::$_instance;

    }


    /**
     * Get application view path
     *
     * @return \string
     */
    public function getViewPath()
    {
        return $this->_bootstrap->getViewPath();

    }


    /**
     * Constructor, use static method `init` to get an instance
     *
     * @param \string $appNamespace Application namespace
     * @param \string $appPath      Application path
     *
     * @throws WebApp\Exception
     *
     * @return \void
     */
    private function __construct($appNamespace, $appPath)
    {
        $this->_appNamespace = $appNamespace;
        $this->_appPath      = $appPath;

        $bootstrapPath = $this->_appPath . '/Bootstrap.php';
        if (!file_exists($bootstrapPath)) {
            throw new WebApp\Exception('Error boostraping!');
        }

        include $bootstrapPath;

        $bsClass = '\\' . $this->_appNamespace . '\\Bootstrap';

        $this->_bootstrap = new $bsClass($this->_appNamespace);

    }


}
