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
 * @package    SLiib_Controller
 * @subpackage SLiib_Controller_View
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Controller_View
 *
 * @package    SLiib_Controller
 * @subpackage SLiib_Controller_View
 */
class SLiib_Controller_View
{

    /**
     * Path of the .phtml view
     * @var string $_view
     */
    private $_view = null;


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
        $path        = SLiib_Application::getInstance()->getViewPath();
        $this->_view = realpath(
            $path . DIRECTORY_SEPARATOR .
            'scripts' . DIRECTORY_SEPARATOR .
            $controller . DIRECTORY_SEPARATOR .
            $action . '.phtml'
        );

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
     * @return mixed
     */
    public function __get($attr)
    {
        return $this->$attr;

    }


    /**
     * Display view
     *
     * @return void
     */
    public function display()
    {
        if ($this->_view) {
            include $this->_view;
        } else {
            echo 'no render';
        }

    }


}
