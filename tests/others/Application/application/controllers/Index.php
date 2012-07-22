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

/**
 * Test controller
 *
 * @package    Tests
 * @subpackage ApplicationTest
 */
class Index extends Controller
{
    /**
     * Init controller
     *
     * @return void
     */
    protected function init()
    {
        $this->_view->bigtitle = 'Index controller!';
    }

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_view->title    = 'Index action!';
        $this->_view->message  = 'Cette application est un test pour les composants ';
        $this->_view->message .= 'SLiib_WebApp, SLiib_Autoloader, ' . PHP_EOL;
        $this->_view->message .= 'SLiib_WebApp_Bootstrap, SLiib_WebApp_Dispatcher, ';
        $this->_view->message .= 'SLiib_WebApp_Controller, SLiib_WebApp_View, ';
        $this->_view->message .= 'SLiib_WebApp_Request.';
        $this->_view->tests    = array(
                                  '/test/model/'         => 'Model',
                                  '/test/library/'       => 'Library',
                                  '/test/noview'         => 'NoView',
                                  '/test/customView'     => 'CustomView',
                                  '/test/javascript'     => 'JavaScript',
                                  '/test/request/'       => 'Request',
                                  '/test/badsetview'     => 'Bad View',
                                  '/test/badpartial'     => 'Bad Partial',
                                  '/test/getterview'     => 'Getter View for units tests',
                                  '/test/badsetviewctrl' => 'Bad Set View in controller',
                                  '/test/errorhandler'   => 'Error Handler test',
                                  '/test/session/'       => 'Session',
                                 );

    }
}

