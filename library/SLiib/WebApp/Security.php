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
 * @package    SLiib\WebApp
 * @subpackage Security
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp;

/**
 * \SLiib\WebApp\Security
 *
 * @package    SLiib\WebApp
 * @subpackage Security
 */
abstract class Security
{


    /**
     * Checkers running
     *
     * @param array $checkers Checkers to run
     *
     * @throws Security\Exception\CheckerError
     *
     * @return void
     */
    public static function check(array $checkers)
    {
        ksort($checkers);
        foreach ($checkers as $checker) {
            if (!$checker instanceof Security\Model) {
                throw new Security\Exception\CheckerError(
                    $checker . ' not appear to be a valid security checker'
                );
            }

            $checker->run();
        }

    }


}
