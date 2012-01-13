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
 * @subpackage Stubs
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */


namespace SLiib;


/**
 * Rewrite php_sapi_name internal function in SLiib namespace
 *
 * @return \string
 */
function php_sapi_name()
{
    return \Stubs\Sapi::getSapi();

}


namespace Stubs;

/**
 * This stub is used to manipulate result of internal function php_sapi_name()
 *
 * @package    Tests
 * @subpackage Stubs
 */
class Sapi
{

    /**
     * Fake php sapi name
     * @var \string
     */
    private static $_sapi = 'stub';


    /**
     * Fake sapi setter
     *
     * @param \string $value Value to set
     *
     * @return \void
     */
    public static function setSapi($value)
    {
        static::$_sapi = $value;

    }


    /**
     * Fake sapi setter
     *
     * @return \string
     */
    public static function getSapi()
    {
        return static::$_sapi;

    }


}