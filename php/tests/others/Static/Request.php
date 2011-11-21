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
 * @subpackage UnitTests_-_Static
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * Static_Request
 *
 * @package    SLiib
 * @subpackage UnitTests_-_Static
 */
class Static_Request
{


    /**
     * Set index of $_SERVER for tests
     *
     * @param string $index Index of $_SERVER
     * @param string $value Value to assign
     *
     * @return void
     */
    static public function setServerInfo($index, $value)
    {
        $GLOBALS['_SERVER'];
        $_SERVER[$index] = $value;

    }


    /**
     * Simulate a $_POST
     *
     * @param array $post $_POST you want
     *
     * @return void
     */
    static public function setPost(array $post)
    {
        $GLOBALS['_POST'];
        $_POST = $post;

    }


    /**
     * Simulate a $_COOKIE
     *
     * @param array $cookies $_COOKIE you want
     *
     * @return void
     */
    static public function setCookie(array $cookies)
    {
        $GLOBALS['_COOKIE'];
        $_COOKIE = $cookies;

    }


}
