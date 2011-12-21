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

/**
 * MyControllerWithMultiDash controller
 *
 * @package    Tests
 * @subpackage ApplicationTest
 */
class Test_Controller_MyControllerWithMultiDash extends SLiib_WebApp_Controller
{


    /**
     * Init controller
     *
     * @return void
     */
    protected function _init()
    {
        $this->_view->bigtitle = 'MyControllerWithMultiDash controller!';

    }


    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_view->title = 'Index action!';

    }


    /**
     * myActionWithMultiDash action
     *
     * @return void
     */
    public function myActionWithMultiDashAction()
    {
        $this->_view->title = 'MyActionWithMultiDash action!';

    }


}