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
 * @subpackage Exception
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_WebApp_Security_Exception_HackingAttempt
 *
 * @package    SLiib_WebApp_Security
 * @subpackage Exception
 */
class SLiib_WebApp_Security_Exception_HackingAttempt
extends SLiib_WebApp_Security_Exception
{

    /**
     * Checker name
     * @var string
     */
    private $_checkerName;

    /**
     * Rule
     * @var SLiib_WebApp_Security_Rule
     */
    private $_rule;

    /**
     * Rule location
     * @var string
     */
    private $_location;

    /**
     * Exception reason
     * @var string
     */
    private $_reason;


    /**
     * Exception constructor
     *
     * @param string                     $checkerName Checker name
     * @param SLiib_WebApp_Security_Rule $rule        Rule
     * @param string                     $location    Location check failed
     * @param string                     $reason      Exception reason
     * @param int                        $code        Exception code
     * @param Exception                  $parent      Parent exception
     *
     * @return void
     */
    public function __construct(
        $checkerName,
        SLiib_WebApp_Security_Rule $rule,
        $location,
        $reason,
        $code=0,
        $parent=NULL
    ) {
        $this->_checkerName = $checkerName;
        $this->_rule        = $rule;
        $this->_location    = $location;
        $this->_reason      = $reason;

        $message = sprintf(
            'Hacking Attempt :: [%s] : [%d] %s in %s (%s)',
            $checkerName,
            $rule->getId(),
            $rule->getMessage(),
            $location,
            $reason
        );

        parent::__construct($message, $code, $parent);

    }


    /**
     * Checker name getter
     *
     * @return string
     */
    public function getCheckerName()
    {
        return $this->_checkerName;

    }


    /**
     * Rule getter
     *
     * @return SLiib_WebApp_Security_Rule
     */
    public function getRule()
    {
        return $this->_rule;

    }


    /**
     * Location getter
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->_location;

    }


    /**
     * Reason exception getter
     *
     * @return string
     */
    public function getReason()
    {
        return $this->_reason;

    }


}
