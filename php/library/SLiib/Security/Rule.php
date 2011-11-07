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
 * @subpackage SLiib_Security_Rule
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Rule
 *
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Rule
 */
class SLiib_Security_Rule
{

    /**
     * Rule id
     * @var int
     */
    private $_id;

    /**
     * Rule name
     * @var string
     */
    private $_name;

    /**
     * Rule pattern
     * @var string
     */
    private $_pattern;

    /**
     * Rule location
     * @var array
     */
    private $_location;


    /**
     * Rule init
     *
     * @param int    $id       Rule Id
     * @param string $name     Rule name
     * @param string $pattern  Rule pattern
     * @param mixed  $location Rule location
     *
     * @return void
     */
    public function __construct($id, $name, $pattern, $location)
    {
        $this->id   = $id;
        $this->name = $name;

        $this->setPattern($pattern);
        $this->setLocation($location);

    }


    /**
     * Id getter
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;

    }


    /**
     * Name getter
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;

    }


    /**
     * Pattern getter
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->_pattern;

    }


    /**
     * Location getter
     *
     * @return array
     */
    public function getLocation()
    {
        return $this->_location;

    }


    /**
     * Pattern setter
     *
     * @param string $pattern Pattern to set
     *
     * @return void
     */
    public function setPattern($pattern)
    {
        $this->_pattern = $pattern;

    }


    /**
     * Location setter
     *
     * @param mixed $location Location tu set
     *
     * @return void
     */
    public function setLocation($location)
    {
        if (!is_array($location)) {
            $location = array($location);
        }

        $this->_location = $location;

    }


}
