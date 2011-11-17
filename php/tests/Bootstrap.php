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
 * @subpackage UnitTests
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.1
 * @link       http://www.sliim-projects.eu
 */
require_once 'SLiib/Autoloader.php';

define('STUBS_PATH', realpath(dirname(__FILE__) . '/others/Stubs'));
define('STATIC_PATH', realpath(dirname(__FILE__) . '/others/Static'));

SLiib_Autoloader::init(
    array(
     'SLiib'  => 'SLiib',
     'Stubs'  => STUBS_PATH,
     'Static' => STATIC_PATH,
    )
);
