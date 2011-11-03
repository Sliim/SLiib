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
 * @package    SLiib
 * @subpackage Tests
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * Test controller
 *
 * @package    SLiib
 * @subpackage Tests
 */
class Test_Controller_Test extends SLiib_Application_Controller
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
        $model = new Test_Model_MyModel();

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
        $lib = new Lib_Class();

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
     * @return bool
     */
    public function errorhandlerAction()
    {
        $this->_view->setNoView();
        return trigger_error('Test error handler');

    }


}