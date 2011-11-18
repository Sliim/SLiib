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
 * @subpackage SLiib_Security_Exception
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Exception_HackingAttempt
 *
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Exception
 */
class SLiib_Security_Exception_HackingAttempt
extends SLiib_Security_Exception
{

    /**
     * Checker name
     * @var string
     */
    private $_checkerName;

    /**
     * Rule id
     * @var int
     */
    private $_ruleId;

    /**
     * Rule name
     * @var string
     */
    private $_ruleName;

    /**
     * Rule location
     * @var string
     */
    private $_location;


    /**
     * Exception constructor
     *
     * @param string    $checkerName Checker name
     * @param int       $ruleId      Rule id
     * @param string    $ruleName    Rule name
     * @param string    $location    Location check failed
     * @param int       $code        Exception code
     * @param Exception $parent      Parent exception
     *
     * @return void
     */
    public function __construct($checkerName, $ruleId, $ruleName, $location, $code=0, $parent=NULL)
    {
        $this->_checkerName = $checkerName;
        $this->_ruleId      = $ruleId;
        $this->_ruleName    = $ruleName;
        $this->_location    = $location;

        $message = sprintf(
            'Hacking Attempt :: %s : Rule: [%s] [%d] raised in %s',
            $checkerName,
            $ruleName,
            $ruleId,
            $location
        );

        parent::__construct($message, $code, $parent);

    }


}
