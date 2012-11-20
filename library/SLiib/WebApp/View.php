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
 * @subpackage View
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp;

use SLiib\WebApp;

/**
 * \SLiib\WebApp\View
 *
 * @package    SLiib\WebApp
 * @subpackage View
 */
class View
{

    /**
     * Subdirectory of view
     * @var string
     */
    protected $subView = 'scripts';

    /**
     * View file extension
     * @var string
     */
    protected $ext = '.phtml';

    /**
     * Path of the .phtml view
     * @var mixed Null if undefined, false if disabled, string if isset
     */
    private $view = null;

    /**
     * Views path
     * @var string
     */
    private $path;

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
        $this->path  = WebApp::getInstance()->getViewPath();
        $defaultView = $controller . DIRECTORY_SEPARATOR . $action;

        if ($this->viewExist($defaultView) && $this->view !== false) {
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
     * @throws Exception\UndefinedProperty
     *
     * @return void
     */
    public function __get($attr)
    {
        throw new Exception\UndefinedProperty(
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
        if (!is_null($this->view) && $this->view) {
            include $this->view;
        }
    }

    /**
     * Set view
     *
     * @param string $view View
     *
     * @throws Exception\InvalidParameter
     *
     * @return void
     */
    public function setView($view)
    {
        if ($viewPath = $this->viewExist($view)) {
            $this->view = realpath($viewPath);
        } else {
            throw new Exception\InvalidParameter(
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
        $this->view = false;
    }

    /**
     * include a template
     * Must be in view path
     *
     * @param string $template Template to include
     *
     * @throws Exception\InvalidParameter
     *
     * @return \SLiib\WebApp\View
     */
    public function partial($template)
    {
        $template = preg_replace(
            '/\.' . substr($this->ext, 1, strlen($this->ext)) . '$/',
            '',
            $template
        );

        $file = $this->path . DIRECTORY_SEPARATOR . $template . $this->ext;
        if (!file_exists($file)) {
            throw new Exception\InvalidParameter(
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
     * @return mixed False if not exist, else absolute path of view
     */
    final private function viewExist($view)
    {
        $absolutePath = $this->path . DIRECTORY_SEPARATOR . $this->subView . DIRECTORY_SEPARATOR;
        $viewFile     = $view . $this->ext;

        if (file_exists($absolutePath . $viewFile)) {
            return $absolutePath . $viewFile;
        }

        return false;
    }
}
