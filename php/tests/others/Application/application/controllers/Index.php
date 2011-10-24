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
class Test_Controller_Index extends SLiib_Controller
{


    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        echo '<h1>Index controller!</h1>' . PHP_EOL;

    }


    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        echo '<h2>Index action!</h2>' . PHP_EOL;
        echo '<pre>Cette application est un test pour les composants SLiib_Application, ';
        echo 'SLiib_Autoloader, SLiib_Bootstrap, SLiib_Dispatcher, SLiib_HTTP_Request.</pre>';
        echo '<br /><br />' . PHP_EOL;

        echo '<ul>' . PHP_EOL;
        echo '<li>Test <a href="/test/model/">Model</a></li>' . PHP_EOL;
        echo '<li>Test <a href="/test/library/">Library</a></li>' . PHP_EOL;
        echo '<li>Test <a href="/test/pget/">Param Get</a></li>' . PHP_EOL;//TODO
        echo '<li>Test <a href="/test/ppost/">Param Post</a></li>' . PHP_EOL;//TODO
        echo '<li>Test <a href="/test/session/">Session</a></li>' . PHP_EOL;//TODO
        echo '</ul>' . PHP_EOL;

    }


}