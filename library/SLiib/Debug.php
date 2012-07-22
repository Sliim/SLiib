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
 * @category SLiib
 * @package  SLiib\Debug
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\Debug
 *
 * @package SLiib\Debug
 */
class Debug
{
    /**
     * Dump a variable
     *
     * @param mixed   $var  Variable to dump
     * @param boolean $echo Print dump in stdout
     * @param boolean $pre  Allow <pre> tags,
     *                       if disallow, dump no contains <pre> tags with any sapi value
     *
     * @return string
     */
    public static function dump($var, $echo = true, $pre = true)
    {
        ob_start();
        var_dump($var);
        $dump = ob_get_contents();
        ob_end_clean();

        if ($pre && php_sapi_name() !== 'cli') {
            $dump = '<pre>' . $dump . '</pre>';
        }

        if ($echo) {
            echo $dump;
        }

        return $dump;
    }
}

