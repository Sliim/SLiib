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
 * @package    SLiib_WebApp
 * @subpackage View
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_WebApp_View
 *
 * @package    SLiib_WebApp
 * @subpackage View
 */
class SLiib_WebApp_View
{

    /**
     * Subdirectory of view
     * @var string
     */
    protected $_subView = 'scripts';

    /**
     * View file extension
     * @var string
     */
    protected $_ext = '.phtml';

    /**
     * Path of the .phtml view
     * @var mixed Null if undefined, false if disabled, string if isset
     */
    private $_view = NULL;

    /**
     * Views path
     * @var string
     */
    private $_path;


    /**
     * Construct, set view path
     *
     * @param string $controller Controller Name
     * @param string $action     Action Name
     *
     * @return void
     */
    public function __construct($controller, $action)
    {
        $this->_path = SLiib_WebApp::getInstance()->getViewPath();
        $defaultView = $controller . DIRECTORY_SEPARATOR . $action;

        if ($this->_viewExist($defaultView) && $this->_view !== FALSE) {
            $this->setView($defaultView);
        }

    }


    /**
     * Set a view attribut
     *
     * @param string $attr  Attribut name
     * @param mixed  $value Attribut value
     *
     * @return void
     */
    public function __set($attr, $value)
    {
        $this->$attr = $value;

    }


    /**
     * Get a view attribut
     *
     * @param string $attr Attribut name
     *
     * @throws SLiib_WebApp_Exception_UndefinedProperty
     *
     * @return void
     */
    public function __get($attr)
    {
        throw new SLiib_WebApp_Exception_UndefinedProperty(
            'Attribut `' . $attr . '` undefined in view.'
        );

    }


    /**
     * Display view
     *
     * @return void
     */
    public function display()
    {
        if (!is_null($this->_view) && $this->_view) {
            include $this->_view;
        }

    }


    /**
     * Set view
     *
     * @param string $view View
     *
     * @throws SLiib_WebApp_Exception_InvalidParameter
     *
     * @return void
     */
    public function setView($view)
    {
        if ($viewPath = $this->_viewExist($view)) {
            $this->_view = realpath($viewPath);
        } else {
            throw new SLiib_WebApp_Exception_InvalidParameter(
                'View `' . $view . '` is invalid.'
            );
        }

    }


    /**
     * Set no view
     *
     * @return void
     */
    public function setNoView()
    {
        $this->_view = FALSE;

    }


    /**
     * include a template
     * Must be in view path
     *
     * @param string $template Template to include
     *
     * @throws SLiib_WebApp_Exception_InvalidParameter
     *
     * @return SLiib_WebApp_View
     */
    public function partial($template)
    {
        $template = preg_replace(
            '/\.' . substr($this->_ext, 1, strlen($this->_ext)) . '$/',
            '',
            $template
        );

        $file = $this->_path . DIRECTORY_SEPARATOR . $template . $this->_ext;
        if (!file_exists($file)) {
            throw new SLiib_WebApp_Exception_InvalidParameter(
                'Partial template ' . $template . ' not found'
            );
        }

        include $file;
        return $this;

    }


    /**
     * Check view exists
     *
     * @param string $view View (without extension)
     *
     * @return boolean|string False if not exist, else absolute path of view
     */
    private final function _viewExist($view)
    {
        $absolutePath = $this->_path . DIRECTORY_SEPARATOR . $this->_subView . DIRECTORY_SEPARATOR;
        $viewFile     = $view . $this->_ext;

        if (file_exists($absolutePath . $viewFile)) {
            return $absolutePath . $viewFile;
        }

        return FALSE;

    }


}