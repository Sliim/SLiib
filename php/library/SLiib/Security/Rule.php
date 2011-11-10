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
    private $_location = array();

    /**
     * Element Pattern
     * @var array
     */
    private $_patternElements = array();


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
    public function __construct($id, $name, $pattern=NULL, $location=NULL)
    {
        $this->_id   = $id;
        $this->_name = $name;

        if (!is_null($pattern)) {
            $this->setPattern($pattern);
        }

        if (!is_null($location)) {
            $this->setLocation($location);
        }

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
     * This disable element pattern !
     *
     * @param string $pattern Pattern to set
     *
     * @return SLiib_Security_Rule
     */
    public function setPattern($pattern)
    {
        $this->_pattern = $pattern;
        return $this;

    }


    /**
     * Location setter
     *
     * @param mixed $location Location tu set
     *
     * @return SLiib_Security_Rule
     */
    public function setLocation($location)
    {
        if (!is_array($location)) {
            $location = array($location);
        }

        $this->_location = $location;
        return $this;

    }


    /**
     * Add an element Pattern
     *
     * @param mixed $element Element to add
     *
     * @return SLiib_Security_Rule
     */
    public function addPatternElement($element)
    {
        if (is_array($element)) {
            $this->_patternElements = array_merge($this->_patternElements, $element);
        } else {
            array_push($this->_patternElements, $element);
        }

        $this->_reloadPattern();
        return $this;

    }


    /**
     * Reload rule pattern
     *
     * @return void
     */
    private function _reloadPattern()
    {
        $pattern = '(' . implode('|', $this->_patternElements) . ')';
        $this->setPattern($pattern);

    }


}
