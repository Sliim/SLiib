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

require 'SLiib/Application.php';

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('APP_PATH', realpath(ROOT_PATH . '/application/'));
define('APP_NS', 'Test');

echo $_SERVER['REQUEST_URI'];

$app = SLiib_Application::init(APP_NS, APP_PATH);

$app->setNamespaces(
    array(
     'SLiib' => 'SLiib',
     'Lib'   => ROOT_PATH . '/library/Test',
    )
);

$app->setSections(
    array(
     'Model'      => 'models',
     'Controller' => 'controllers',
    )
);

$app->run();

$model = new Test_Model_MyModel();
Test_Controller_Index::indexAction();
$lib = new Lib_Class();