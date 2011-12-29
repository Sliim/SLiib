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

namespace Stubs\Security;

/**
 * Stubs\Security\Model
 *
 * @package    Tests
 * @subpackage Stubs
 */
abstract class Model extends \SLiib\WebApp\Security\Model
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
     * @param \SLiib\WebApp\Security\Rule $rule   Rule to check
     * @param string                      $string String to use
     *
     * @return boolean
     */
    protected function _check($rule, $string)
    {
        if (preg_match('/' . $rule->getPattern() . '/' . $rule->getFlags(), $string)) {
            return FALSE;
        }

        return TRUE;

    }


}
