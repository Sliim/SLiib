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
 * @package  SLiib_Application
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */
require_once 'SLiib/Autoloader.php';

/**
 * SLiib_Application
 *
 * @package SLiib_Application
 */
class SLiib_Application
{

    /**
     * Instance
     * @var SLiib_Application $_instance
     */
    private static $_instance = null;

    /**
     * Application namespace
     * @var string $_appNameSpace
     */
    private $_appNamespace;

    /**
     * Application path
     * @var string $_appPath
     */
    private $_appPath;

    /**
     * Namespaces to include
     * @var array $_namespaces
     */
    private $_namespaces;

    /**
     * Sections to include
     * @var array $_sections
     */
    private $_sections;

    /**
     * Application bootstrap
     * @var string $_bootstrap
     */
    private $_bootstrap;

    /**
     * Application bootstrap path
     * @var string $_bootstrapPath
     */
    private $_bootstrapPath;


    /**
     * Constructor, use static method `init` to get an instance
     *
     * @param string $appNamespace Application namespace
     * @param string $appPath      Application path
     * @param array  $namespaces   Namespaces
     * @param array  $sections     Sections
     *
     * @throws SLiib_Application_Exception
     *
     * @return void
     */
    private function __construct(
        $appNamespace,
        $appPath,
        array $namespaces,
        array $sections
    ) {
        $this->_appNamespace = $appNamespace;
        $this->_appPath      = $appPath;
        $this->_namespaces   = $namespaces;
        $this->_sections     = $sections;

        $bsPath = $this->_appPath . '/Bootstrap.php';
        if (!file_exists($bsPath)) {
            throw new SLiib_Application_Exception('Error boostraping!');
        }

        $this->_bootstrapPath = $bsPath;
        $this->_bootstrap     = $this->_appNamespace . '_Bootstrap';

    }


    /**
     * Application Init
     *
     * @param string          $appNamespace Application namespace
     * @param string          $appPath      Application path
     * @param array[optional] $namespaces   Namespaces
     * @param array[optional] $sections     Sections
     *
     * @return SLiib_Application
     */
    public static function init(
        $appNamespace,
        $appPath,
        array $namespaces=array(),
        array $sections=array()
    ) {
        if (!is_null(self::$_instance)) {
            return self::$_instance;
        }

        SLiib_Autoloader::init(array('SLiib' => 'SLiib'));

        self::$_instance = new self(
            $appNamespace,
            $appPath,
            $namespaces,
            $sections
        );

        return self::$_instance;

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
     * Run application
     *
     * @return void
     */
    public function run()
    {
        $namespaces = array_merge(
            array($this->_appNamespace => $this->_appPath),
            $this->_namespaces
        );

        SLiib_Autoloader::init($namespaces, $this->_sections);

        include $this->_bootstrapPath;
        class_alias($this->_bootstrap, 'Bootstrap');
        Bootstrap::run();

    }


}
