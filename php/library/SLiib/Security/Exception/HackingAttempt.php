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

    private $_name;
    private $_type;
    private $_location;


    public function __construct($name, $type, $location, $code=0, $parent=null) {
        $this->_name = $name;
        $this->_type = $type;
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
