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
 * @package    SLiib_Application
 * @subpackage SLiib_Application_Controller
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Application_Controller
 *
 * @package    SLiib_Application
 * @subpackage SLiib_Application_Controller
 */
abstract class SLiib_Application_Controller
{

    /**
     * View of this controller/action
     * @var SLiib_Application_View
     */
    protected $_view = NULL;

    /**
     * View Class
     * @var string
     */
    protected $_viewClass = 'SLiib_Application_View';

    /**
     * @var SLiib_HTTP_Request
     */
    protected $_request = NULL;


    /**
     * Init controller view
     *
     * @return void
     */
    public function __construct()
    {
        $this->_request = SLiib_HTTP_Request::getInstance();
        $this->_view    = new $this->_viewClass(
            $this->_request->getController(),
            $this->_request->getAction()
        );

    }


    /**
     * Appel des action du controller.
     *
     * @param string $action Action to call
     * @param array  $params unused
     *
     * @throws SLiib_Application_Controller_Exception_BadMethodCall
     *
     * @return void
     */
    public final function __call($action, $params)
    {
        unset($params);
        $method = $action . 'Action';

        if (!method_exists($this, $method)) {
            throw new SLiib_Application_Controller_Exception_BadMethodCall(
                'Action `' . $action . '` is invalid.'
            );
        }

        $this->_init();
        $this->$method();
        $this->_view->display();

    }


    /**
     * Request getter
     *
     * @return SLiib_HTTP_Request
     */
    public function getRequest()
    {
        return $this->_request;

    }


    /**
     * Init controller, called before action
     *
     * @return void
     */
    abstract protected function _init();


}
