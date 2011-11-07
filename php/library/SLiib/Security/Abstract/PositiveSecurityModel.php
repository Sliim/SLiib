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
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Abstract
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Abstract_PositiveSecurityModel
 *
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Abstract
 */
abstract class SLiib_Security_Abstract_PositiveSecurityModel
extends SLiib_Security_Abstract
{

    /**
     * Security model
     * @var string
     */
    protected $_model = self::MODEL_POSITIVE;

    /**
     * Valeurs autorisée
     * @var array
     */
    protected $_allowed;


    /**
     * Add allowed element to pattern
     *
     * @param int    $ruleId  Rule affected
     * @param string $element Element to add
     *
     * @return SLiib_Security_Abstract_PositiveSecurityModel
     */
    public function addAllowedElement($ruleId, $element)
    {
        if (is_array($element)) {
            $this->_allowed = array_merge($this->_allowed, $element);
        } else {
            array_push($this->_allowed, $element);
        }

        $this->_reloadPattern($ruleId);

        return $this;

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
            return TRUE;
        }

        return FALSE;

    }


}