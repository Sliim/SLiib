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
 * @package    SLiib_WebApp_Security
 * @subpackage Abstract
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_WebApp_Security_Abstract_NegativeSecurityModel
 *
 * @package    SLiib_WebApp_Security
 * @subpackage Abstract
 */
abstract class SLiib_WebApp_Security_Abstract_NegativeSecurityModel
extends SLiib_WebApp_Security_Abstract
{

    /**
     * Security model
     * @var string
     */
    protected $_model = self::MODEL_NEGATIVE;


    /**
     * Check a pattern in a string
     *
     * @param SLiib_WebApp_Security_Rule $rule   Rule to check
     * @param string              $string String to use
     *
     * @return boolean
     */
    protected function _check($rule, $string)
    {
        if (preg_match('/' . $rule->getPattern() . '/' . $rule->getFlags(), $string)) {
            $this->_patternError = $string;
            return FALSE;
        }

        return TRUE;

    }


}