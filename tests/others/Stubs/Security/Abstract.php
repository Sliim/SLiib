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
 * @package    Tests
 * @subpackage Stubs
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * Stubs_Security_BadSecModel
 *
 * @package    Tests
 * @subpackage Stubs
 */
abstract class Stubs_Security_Abstract extends SLiib_Security_Abstract
{


    /**
     * Construct - Set model security
     *
     * @param string[optional] $model Model to set
     *
     * @return void
     */
    public function __construct($model='')
    {
        $this->_model   = $model;
        $this->_setName = 'Stubs Checker';
        parent::__construct();

    }


    /**
     * Check a pattern in a string
     *
     * @param string $pattern Pattern to check
     * @param string $string  String to use
     *
     * @return boolean
     */
    protected function _check($pattern, $string)
    {
        if (preg_match('/' . $pattern . '/', $string)) {
            return FALSE;
        }

        return TRUE;

    }


}
