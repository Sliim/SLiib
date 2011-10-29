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
 * @package  SLiib_Security
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Exception
 *
 * @package SLiib
 */
class SLiib_Security_Exception_HackingAttempt
extends SLiib_Security_Exception
{

    /**
     * Checker name
     * @var string $_name
     */
    private $_name;

    /**
     * Pattern type
     * @var string $_type
     */
    private $_type;

    /**
     * Pattern location
     * @var string $_location
     */
    private $_location;


    /**
     * Exception constructor
     *
     * @param string    $name     Checker name
     * @param string    $type     Pattern type
     * @param string    $location Location check failed
     * @param int       $code     Exception code
     * @param Exception $parent   Parent exception
     *
     * @return void
     */
    public function __construct($name, $type, $location, $code=0, $parent=null)
    {
        $this->_name     = $name;
        $this->_type     = $type;
        $this->_location = $location;

        $message = sprintf(
            'Hacking Attempt type : %s name : %s in %s',
            $type,
            $name,
            $location
        );

        parent::__construct($message, $code, $parent);

    }


}
