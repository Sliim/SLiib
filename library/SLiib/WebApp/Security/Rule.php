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
 * @version    Release: 0.2
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

    /**
     * Location constantes
     * @var \string
     */
    const LOCATION_REQUEST_URI = 'Request URI';
    const LOCATION_PARAMETERS  = 'Parameters';
    const LOCATION_USERAGENT   = 'UserAgent';
    const LOCATION_HTTP_METHOD = 'HTTP Method Request';
    const LOCATION_COOKIES     = 'Cookies';
    const LOCATION_REFERER     = 'Referer';

    /**
     * Rule id
     * @var \int
     */
    private $_id;

    /**
     * Rule message
     * @var \string
     */
    private $_message;

    /**
     * Rule pattern
     * @var \string
     */
    private $_pattern;

    /**
     * Rule locations
     * @var \array
     */
    private $_locations = array();

    /**
     * Element Pattern
     * @var \array
     */
    private $_patternElements = array();

    /**
     * preg quote enabled
     * @var \boolean
     */
    private $_pregQuoteEnabled = FALSE;

    /**
     * Flags for regular expression
     * @var \array
     */
    private $_flags = array();


    /**
     * Rule init
     *
     * @param \int              $id       Rule Id
     * @param \string           $message  Rule message
     * @param \string[optional] $pattern  Rule pattern
     * @param \mixed[optional]  $location Rule location
     *
     * @return \void
     */
    public function __construct($id, $message, $pattern=NULL, $location=NULL)
    {
        $this->_id      = $id;
        $this->_message = $message;

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
     * @return \int
     */
    public function getId()
    {
        return $this->_id;

    }


    /**
     * Message getter
     *
     * @return \string
     */
    public function getMessage()
    {
        return $this->_message;

    }


    /**
     * Pattern getter
     *
     * @return \string
     */
    public function getPattern()
    {
        return $this->_pattern;

    }


    /**
     * Location getter
     *
     * @return \array
     */
    public function getLocation()
    {
        return $this->_locations;

    }


    /**
     * Get rule flags
     *
     * @return \string
     */
    public function getFlags()
    {
        return implode('', $this->_flags);

    }


    /**
     * Pattern setter
     * This disable element pattern !
     *
     * @param \string $pattern Pattern to set
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function setPattern($pattern)
    {
        if ($this->_pregQuoteEnabled) {
            $this->_pattern = preg_quote($pattern, '/');
        } else {
            $this->_pattern = $pattern;
        }

        return $this;

    }


    /**
     * Add a rule's location
     *
     * @param \string $location Location to add
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function addLocation($location)
    {
        if (is_array($location)) {
            $this->_locations = array_unique(array_merge($this->_locations, $location));
        } else if (!in_array($location, $this->_locations)) {
            array_push($this->_locations, $location);
        }

        return $this;

    }


    /**
     * Delete a rule's location
     *
     * @param \string $location Location to add
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function deleteLocation($location)
    {
        if (in_array($location, $this->_locations)) {
            $key = array_search($location, $this->_locations);
            unset($this->_locations[$key]);
        }

        return $this;

    }


    /**
     * Add an element Pattern
     *
     * @param \mixed $element Element to add
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function addPatternElement($element)
    {
        if (is_array($element)) {
            $this->_patternElements = array_unique(array_merge($this->_patternElements, $element));
        } else {
            array_push($this->_patternElements, $element);
        }

        $this->_reloadPattern();
        return $this;

    }


    /**
     * Delete an element pattern if exists
     *
     * @param \string $element Element to delete
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function deletePatternElement($element)
    {
        if (in_array($element, $this->_patternElements)) {
            $key = array_search($element, $this->_patternElements);
            unset($this->_patternElements[$key]);

            $this->_reloadPattern();
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
        $this->_pregQuoteEnabled = TRUE;
        return $this;

    }


    /**
     * Disable preg_quote function
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function disablePregQuote()
    {
        $this->_pregQuoteEnabled = FALSE;
        return $this;

    }


    /**
     * Enable case sensitivity for preg_match pattern
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function enableCaseSensitivity()
    {
        if (in_array('i', $this->_flags)) {
            $key = array_search('i', $this->_flags);
            unset($this->_flags[$key]);
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
        if (!in_array('i', $this->_flags)) {
            array_push($this->_flags, 'i');
        }

        return $this;

    }


    /**
     * Reload rule pattern
     *
     * @return \void
     */
    private function _reloadPattern()
    {
        $patternArray = array();
        foreach ($this->_patternElements as $key => $element) {
            if ($this->_pregQuoteEnabled) {
                $patternArray[$key] = preg_quote($element, '/');
            } else {
                $patternArray[$key] = $element;
            }
        }

        $pattern = '(' . implode('|', $patternArray) . ')';

        if ($this->_pregQuoteEnabled) {
            $this->disablePregQuote()->setPattern($pattern)->enablePregQuote();
        } else {
            $this->setPattern($pattern);
        }

    }


}
