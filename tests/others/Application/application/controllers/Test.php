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
 * @package    Tests
 * @subpackage ApplicationTest
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace Test\Controller;
use SLiib\WebApp\Controller,
    SLiib\WebApp\Session;

/**
 * Test controller
 *
 * @package    Tests
 * @subpackage ApplicationTest
 */
class Test extends Controller
{


    /**
     * Init controller
     *
     * @return void
     */
    protected function _init()
    {
        $this->_view->bigtitle = 'Test controller!';

    }


    /**
     * Test model
     *
     * @return void
     */
    public function modelAction()
    {
        $model = new \Test\Model\MyModel();

        $this->_view->title   = 'Test model!';
        $this->_view->message = $model->toString();

    }


    /**
     * Test library
     *
     * @return void
     */
    public function libraryAction()
    {
        $lib = new \Lib\MyClass();

        $this->_view->title   = 'Test library!';
        $this->_view->message = $lib->toString();

    }


    /**
     * Test No view
     *
     * @return void
     */
    public function noviewAction()
    {
        $this->_view->setNoView();
        echo 'No view for this controller';

    }


    /**
     * Test custom view
     *
     * @return void
     */
    public function customViewAction()
    {
        $this->_view->setView('aview');
        $this->_view->message = 'Test custom view';

    }


    /**
     * Test javascript script
     *
     * @return void
     */
    public function javascriptAction()
    {
        //Test appel script js

    }


    /**
     * Test http request
     *
     * @return void
     */
    public function requestAction()
    {
        $this->_view->bigtitle  = 'Test HTTP Request';
        $this->_view->ip        = $this->getRequest()->getClientIp();
        $this->_view->method    = $this->getRequest()->getRequestMethod();
        $this->_view->userAgent = $this->getRequest()->getUserAgent();
        $this->_view->params    = $this->getRequest()->getParameters();
        $this->_view->cookies   = $this->getRequest()->getCookies();
        $this->_view->referer   = $this->getRequest()->getReferer();

    }


    /**
     * Test setting bad view
     *
     * @return void
     */
    public function badsetviewAction()
    {
        return $this->_view->setView('notexists');

    }


    /**
     * Test setting bad partial
     *
     * @return void
     */
    public function badPartialAction()
    {
        //Nothing to do

    }


    /**
     * Test get unknown view property
     *
     * @return void
     */
    public function getterviewAction()
    {
        $this->_view->setNoView();
        return $woot = $this->_view->woot;

    }


    /**
     * Test error handler
     *
     * @return boolean
     */
    public function errorhandlerAction()
    {
        $this->_view->setNoView();
        return trigger_error('Test error handler');

    }


    /**
     * Test session
     *
     * @return void
     */
    public function sessionAction()
    {
        $this->_view->bigtitle = 'Test Session';
        $this->_view->logged   = FALSE;

        $params  = $this->_request->getParameters();
        $session = new Session('TestSession');

        if (array_key_exists('login', $params)) {
            if (array_key_exists('username', $params) && array_key_exists('password', $params)) {
                if ($params['password'] == 'isSecure') {
                    $session->username = $params['username'];
                    $session->logged   = TRUE;
                }
            }
        } else if (array_key_exists('logout', $params)) {
            $this->_view->logged = FALSE;
            $session->clear();
        }

        if (isset($session->logged)) {
            if ($session->logged) {
                $this->_view->logged   = TRUE;
                $this->_view->username = $session->username;
            }
        }

    }


}