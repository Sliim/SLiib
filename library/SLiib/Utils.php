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
 * @category SLiib
 * @package  SLiib\Utils
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\Utils
 *
 * @package SLiib\Utils
 */
class Utils
{


    /**
     * Merge with an existing object
     *
     * @param mixed &$source Object source
     * @param mixed $object  Object to merge with source
     *
     * @return void
     */
    public static function mergeObject(&$source, $object)
    {
        foreach ($object as $key => $value) {
            if (!isset($source->$key)) {
                $source->$key = $value;
                continue;
            }

            if (is_object($source->$key) && is_object($value)) {
                static::mergeObject($source->$key, $value);
            }
        }

    }


}
