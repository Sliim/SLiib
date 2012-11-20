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
 * @package    Tests
 * @subpackage ApplicationTest
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace Test\Controller;

use SLiib\WebApp\Controller;
use SLiib\WebApp\Session;

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
    protected function init()
    {
        $this->view->bigtitle = 'Test controller!';
    }

    /**
     * Test model
     *
     * @return void
     */
    public function modelAction()
    {
        $model = new \Test\Model\MyModel();

        $this->view->title   = 'Test model!';
        $this->view->message = $model->toString();
    }

    /**
     * Test library
     *
     * @return void
     */
    public function libraryAction()
    {
        $lib = new \Lib\MyClass();

        $this->view->title   = 'Test library!';
        $this->view->message = $lib->toString();
    }

    /**
     * Test No view
     *
     * @return void
     */
    public function noviewAction()
    {
        $this->view->setNoView();
        echo 'No view for this controller';
    }

    /**
     * Test custom view
     *
     * @return void
     */
    public function customViewAction()
    {
        $this->view->setView('aview');
        $this->view->message = 'Test custom view';
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
        $this->view->bigtitle  = 'Test HTTP Request';
        $this->view->ip        = $this->getRequest()->getClientIp();
        $this->view->method    = $this->getRequest()->getRequestMethod();
        $this->view->userAgent = $this->getRequest()->getUserAgent();
        $this->view->params    = $this->getRequest()->getParameters();
        $this->view->cookies   = $this->getRequest()->getCookies();
        $this->view->referer   = $this->getRequest()->getReferer();
    }

    /**
     * Test setting bad view
     *
     * @return void
     */
    public function badsetviewAction()
    {
        return $this->view->setView('notexists');
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
        $this->view->setNoView();
        return $woot = $this->view->woot;
    }

    /**
     * Test error handler
     *
     * @return boolean
     */
    public function errorhandlerAction()
    {
        $this->view->setNoView();
        return trigger_error('Test error handler');
    }

    /**
     * Test session
     *
     * @return void
     */
    public function sessionAction()
    {
        $this->view->bigtitle = 'Test Session';
        $this->view->logged   = false;

        $params  = $this->request->getParameters();
        $session = new Session('TestSession');

        if (array_key_exists('login', $params)) {
            if (array_key_exists('username', $params) && array_key_exists('password', $params)) {
                if ($params['password'] == 'isSecure') {
                    $session->username = $params['username'];
                    $session->logged   = true;
                }
            }
        } elseif (array_key_exists('logout', $params)) {
            $this->view->logged = false;
            $session->clear();
        }

        if (isset($session->logged)) {
            if ($session->logged) {
                $this->view->logged   = true;
                $this->view->username = $session->username;
            }
        }
    }
}
