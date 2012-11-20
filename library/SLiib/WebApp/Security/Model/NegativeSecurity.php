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
 * @subpackage Security\Model
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp\Security\Model;

use SLiib\WebApp\Security\Model;

/**
 * \SLiib\WebApp\Security\Model\NegativeSecurity
 *
 * @package    SLiib\WebApp
 * @subpackage Security\Model
 */
abstract class NegativeSecurity extends Model
{

    /**
     * Security model
     * @var string
     */
    protected $model = self::MODEL_NEGATIVE;

    /**
     * Check a pattern in a string
     *
     * @param \SLiib\WebApp\Security\Rule $rule   Rule to check
     * @param string                      $string String to use
     *
     * @return boolean
     */
    protected function check($rule, $string)
    {
        if (preg_match('/' . $rule->getPattern() . '/' . $rule->getFlags(), $string)) {
            $this->patternError = $string;
            return false;
        }

        return true;
    }
}
