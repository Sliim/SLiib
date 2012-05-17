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
 * @package    SLiib\Utils
 * @subpackage String
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\Utils;

/**
 * \SLiib\Utils\String
 *
 * @package    SLiib\Utils
 * @subpackage String
 */
class String
{


    /**
     * Delete undesirable spaces
     *
     * @param string $string String to clean.
     *
     * @return string
     */
    public static function clean($string)
    {
        $string = preg_replace(
            array(
             "/\t\t+/",
             "/\x20\x20+/",
             "/\xA0\xA0+/",
            ), ' ',
            $string
        );

        $string = preg_replace(
            array(
             "/(\n\s*\n)/",
             "/(\n\s+)/",
            ), "\n",
            $string
        );

        $string = trim($string);
        return $string;

    }


}
