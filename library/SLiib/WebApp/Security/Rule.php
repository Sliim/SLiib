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
 * @subpackage Security
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp\Security;

/**
 * \SLiib\WebApp\Security\Rule
 *
 * @package    SLiib\WebApp
 * @subpackage Security
 */
class Rule
{
    const LOCATION_REQUEST_URI = 'Request URI';
    const LOCATION_PARAMETERS  = 'Parameters';
    const LOCATION_USERAGENT   = 'UserAgent';
    const LOCATION_HTTP_METHOD = 'HTTP Method Request';
    const LOCATION_COOKIES     = 'Cookies';
    const LOCATION_REFERER     = 'Referer';

    /**
     * Rule id
     * @var int
     */
    private $id;

    /**
     * Rule message
     * @var string
     */
    private $message;

    /**
     * Rule pattern
     * @var string
     */
    private $pattern;

    /**
     * Rule locations
     * @var array
     */
    private $locations = array();

    /**
     * Element Pattern
     * @var array
     */
    private $patternElements = array();

    /**
     * preg quote enabled
     * @var boolean
     */
    private $pregQuoteEnabled = false;

    /**
     * Flags for regular expression
     * @var array
     */
    private $flags = array();

    /**
     * Rule init
     *
     * @param int    $id       Rule Id
     * @param string $message  Rule message
     * @param string $pattern  Rule pattern
     * @param mixed  $location Rule location
     *
     * @return void
     */
    public function __construct($id, $message, $pattern = null, $location = null)
    {
        $this->id      = $id;
        $this->message = $message;

        if (!is_null($pattern)) {
            $this->setPattern($pattern);
        }

        if (!is_null($location)) {
            if (!is_array($location)) {
                $location = array($location);
            }

            foreach ($location as $l) {
                $this->addLocation($l);
            }
        }
    }

    /**
     * Id getter
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Message getter
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Pattern getter
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Location getter
     *
     * @return array
     */
    public function getLocation()
    {
        return $this->locations;
    }

    /**
     * Get rule flags
     *
     * @return string
     */
    public function getFlags()
    {
        return implode('', $this->flags);
    }

    /**
     * Pattern setter
     * This disable element pattern !
     *
     * @param string $pattern Pattern to set
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function setPattern($pattern)
    {
        if ($this->pregQuoteEnabled) {
            $this->pattern = preg_quote($pattern, '/');
        } else {
            $this->pattern = $pattern;
        }

        return $this;
    }

    /**
     * Add a rule's location
     *
     * @param string $location Location to add
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function addLocation($location)
    {
        if (is_array($location)) {
            $this->locations = array_unique(array_merge($this->locations, $location));
        } elseif (!in_array($location, $this->locations)) {
            array_push($this->locations, $location);
        }

        return $this;
    }

    /**
     * Delete a rule's location
     *
     * @param string $location Location to add
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function deleteLocation($location)
    {
        if (in_array($location, $this->locations)) {
            $key = array_search($location, $this->locations);
            unset($this->locations[$key]);
        }

        return $this;
    }

    /**
     * Add an element Pattern
     *
     * @param mixed $element Element to add
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function addPatternElement($element)
    {
        if (is_array($element)) {
            $this->patternElements = array_unique(array_merge($this->patternElements, $element));
        } else {
            array_push($this->patternElements, $element);
        }

        $this->reloadPattern();
        return $this;
    }

    /**
     * Delete an element pattern if exists
     *
     * @param string $element Element to delete
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function deletePatternElement($element)
    {
        if (in_array($element, $this->patternElements)) {
            $key = array_search($element, $this->patternElements);
            unset($this->patternElements[$key]);

            $this->reloadPattern();
        }

        return $this;
    }

    /**
     * Enable preg_quote function
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function enablePregQuote()
    {
        $this->pregQuoteEnabled = true;
        return $this;
    }

    /**
     * Disable preg_quote function
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function disablePregQuote()
    {
        $this->pregQuoteEnabled = false;
        return $this;
    }

    /**
     * Enable case sensitivity for preg_match pattern
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function enableCaseSensitivity()
    {
        if (in_array('i', $this->flags)) {
            $key = array_search('i', $this->flags);
            unset($this->flags[$key]);
        }

        return $this;
    }


    /**
     * Disable case sensitivity for preg_match pattern
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function disableCaseSensitivity()
    {
        if (!in_array('i', $this->flags)) {
            array_push($this->flags, 'i');
        }

        return $this;
    }


    /**
     * Reload rule pattern
     *
     * @return void
     */
    private function reloadPattern()
    {
        $patternArray = array();
        foreach ($this->patternElements as $key => $element) {
            if ($this->pregQuoteEnabled) {
                $patternArray[$key] = preg_quote($element, '/');
            } else {
                $patternArray[$key] = $element;
            }
        }

        $pattern = '(' . implode('|', $patternArray) . ')';

        if ($this->pregQuoteEnabled) {
            $this->disablePregQuote()->setPattern($pattern)->enablePregQuote();
        } else {
            $this->setPattern($pattern);
        }
    }
}
