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
 * @subpackage Security\Exception
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp\Security\Exception;

use SLiib\WebApp\Security\Exception as SecurityException;
use SLiib\IException\Runtime as RuntimeException;
use SLiib\WebApp\Security\Rule;

/**
 * \SLiib\WebApp\Security\Exception\HackingAttempt
 *
 * @package    SLiib\WebApp
 * @subpackage Security\Exception
 */
class HackingAttempt extends SecurityException implements RuntimeException
{

    /**
     * Checker name
     * @var string
     */
    private $checkerName;

    /**
     * Security Rule
     * @var \SLiib\WebApp\Security\Rule
     */
    private $rule;

    /**
     * Rule location
     * @var string
     */
    private $location;

    /**
     * Exception reason
     * @var string
     */
    private $reason;

    /**
     * Exception constructor
     *
     * @param string                      $checkerName Checker name
     * @param \SLiib\WebApp\Security\Rule $rule        Rule
     * @param string                      $location    Location check failed
     * @param string                      $reason      Exception reason
     * @param int                         $code        Exception code
     * @param \Exception                  $parent      Parent exception
     *
     * @return void
     */
    public function __construct(
        $checkerName,
        Rule $rule,
        $location,
        $reason,
        $code = 0,
        \Exception $parent = null
    ) {
        $this->checkerName = $checkerName;
        $this->rule        = $rule;
        $this->location    = $location;
        $this->reason      = $reason;

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
        return $this->checkerName;
    }

    /**
     * Rule getter
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Location getter
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Reason exception getter
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}

