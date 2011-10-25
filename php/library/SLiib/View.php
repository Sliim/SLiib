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
 * @package  SLiib_View
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_View
 *
 * @package SLiib_View
 */
class SLiib_View
{

    /**
     * Path of the .phtml view
     * @var mixed $_view null if undefined, false if disabled, string if isset
     */
    private $_view = null;


    /**
     * Subdirectory of view
     * @var string $_subView
     */
    private $_subView = 'scripts';


    /**
     * View file extension
     * @var string $_ext
     */
    private $_ext = '.phtml';


    /**
     * Views path
     * @var string $_path
     */
    private $_path;


    /**
     * Construct, set view path
     *
     * @param string $controller Controller name
     * @param string $action     Action name
     *
     * @return void
     */
    public function __construct($controller, $action)
    {
        //TODO Voir si ya pas un moyen plus propre..
        $this->_path = SLiib_Application::getInstance()->getViewPath();

        $defaultView = $controller . DIRECTORY_SEPARATOR . $action;

        if ($this->_viewExist($defaultView) && $this->_view !== false) {
            $this->setView($defaultView);
        }

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
     * @return void
     */
    public function setView($view)
    {
        if ($viewPath = $this->_viewExist($view)) {
            $this->_view = realpath($viewPath);
        } else {
            //TODO Change exception to throw
           throw new SLiib_Controller_Exception('View `' . $view . '` not found.');
        }

    }


    /**
     * Set no view
     *
     * @return void
     */
    public function setNoView()
    {
        $this->_view = false;

    }


    /**
     * Set a view attribut
     *
     * @param string $attr  Attribut name
     * @param mixed  $value Attribut value
     *
     * @return void
     */
    private function __set($attr, $value)
    {
        $this->$attr = $value;

    }


    /**
     * Get a view attribut
     *
     * @param string $attr Attribut name
     *
     * @return mixed
     */
    private function __get($attr)
    {
        return $this->$attr;

    }


    /**
     * Check view exists
     *
     * @param string View (without extension)
     *
     * @return boolean|string False if not exist, else absolute path of view
     */
    private function _viewExist($view)
    {
        $absolutePath = $this->_path . DIRECTORY_SEPARATOR . $this->_subView . DIRECTORY_SEPARATOR;
        $viewFile     = $view . $this->_ext;

        if (file_exists($absolutePath . $viewFile)) {
            return $absolutePath . $viewFile;
        }

        return false;

    }


}
