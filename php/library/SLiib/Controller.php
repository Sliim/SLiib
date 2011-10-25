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
 * @package  SLiib_Controller
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Controller
 *
 * @package SLiib_Controller
 */
abstract class SLiib_Controller
{

    /**
     * Controller name
     * @var string $_name
     */
    private $_name = null;

    /**
     * View of this controller/action
     * @var SLiib_View $_view
     */
    protected $_view = null;


    /**
     * Construct
     * Init name controller and the View
     *
     * @param string $name   Controller name
     * @param string $action Action name
     *
     * @return void
     */
    public function __construct($name, $action)
    {
        $this->_name = $name;
        $this->_view = new SLiib_View($name, $action);

    }


    /**
     * Init controller, called before action
     *
     * @return void
     */
    abstract protected function _init();


    /**
     * Appel des action du controller.
     *
     * @param string $action Action to call
     * @param array  $params unused
     *
     * @return void
     */
    public function __call($action, $params)
    {
        $method = $action . 'Action';

        if (!method_exists($this, $method)) {
            throw new SLiib_Controller_Exception_ActionNotFound('No action found.');
        }

        $this->_init();
        $this->$method();
        $this->_view->display();

    }


}
