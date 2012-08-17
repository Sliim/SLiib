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
 * @subpackage Controller
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp;

/**
 * \SLiib\WebApp\Controller
 *
 * @package    SLiib\WebApp
 * @subpackage Controller
 */
abstract class Controller
{

    /**
     * View of this controller/action
     * @var \SLiib\WebApp\View
     */
    protected $view = null;

    /**
     * View Class
     * @var string
     */
    protected $viewClass = '\SLiib\WebApp\View';

    /**
     * Request instance
     * @var \SLiib\WebApp\Request
     */
    protected $request = null;

    /**
     * Init controller view
     *
     * @return void
     */
    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->view    = new $this->viewClass(
            $this->request->getController(),
            $this->request->getAction()
        );
    }

    /**
     * Get called action and run it
     *
     * @param string $action Action to call
     * @param array  $params unused
     *
     * @throws \SLiib\WebApp\Exception\NoDispatchable
     *
     * @return void
     */
    final public function __call($action, array $params)
    {
        unset($params);
        $method = $action . 'Action';

        if (!method_exists($this, $method)) {
            throw new Exception\NoDispatchable(
                'Action `' . $action . '` is invalid.'
            );
        }

        $this->init();
        $this->$method();
        $this->view->display();
    }

    /**
     * Request getter
     *
     * @return \SLiib\WebApp\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Init controller, called before action
     *
     * @return void
     */
    abstract protected function init();
}

