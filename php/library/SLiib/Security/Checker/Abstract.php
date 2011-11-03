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
 * @subpackage SLiib_Security_Checker_Abstract
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Checker_Abstract
 *
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Checker_Abstract
 */
abstract class SLiib_Security_Checker_Abstract
{

    /**
     * Location contantes
     */
    const LOCATION_PARAMETERS  = 'Parameters';
    const LOCATION_USERAGENT   = 'UserAgent';
    const LOCATION_HTTP_METHOD = 'Method';
    const LOCATION_COOKIES     = 'Cookies';
    const LOCATION_REFERER     = 'Referer';

    /**
     * Checker name
     * @var string
     */
    private $_name;

    /**
     * List of patterns
     * @var array
     */
    private $_patterns = array();

    /**
     * Request object
     * @var SLiib_HTTP_Request
     */
    private $_request;


    /**
     * Running checker
     *
     * @throws SLiib_Security_Exception_CheckerError
     * @throws SLiib_Security_Exception_HackingAttempt
     *
     * @return void
     */
    public final function run()
    {
        $this->_request = SLiib_HTTP_Request::getInstance();

        foreach ($this->_patterns as $pattern) {
            foreach ($pattern->locations as $location) {
                $attempt = TRUE;
                switch ($location) {
                    case self::LOCATION_PARAMETERS:
                        $attempt = $this->_checkParameters($pattern->str);
                        break;
                    case self::LOCATION_USERAGENT:
                        $attempt = $this->_checkUserAgent($pattern->str);
                        break;
                    case self::LOCATION_HTTP_METHOD:
                        $attempt = $this->_checkMethod($pattern->str);
                        break;
                    case self::LOCATION_COOKIES:
                        //TODO
                        break;
                    case self::LOCATION_USERAGENT:
                        //TODO
                        break;
                    default:
                        throw new SLiib_Security_Exception_CheckerError(
                            'Location for `' . $this->_name . '` checker is not valid'
                        );
                        break;
                }

                if (!$attempt) {
                    throw new SLiib_Security_Exception_HackingAttempt(
                        $this->_name, $pattern->type, $location
                    );
                }
            }
        }

    }


    /**
     * Set checker name
     *
     * @param string $name Checker name
     *
     * @return void
     */
    protected final function _setName($name)
    {
        $this->_name = $name;

    }


    /**
     * Add a pattern
     *
     * @param string $pattern   Pattern to add
     * @param string $type      Pattern type
     * @param mixed  $locations Pattern locations
     *
     * @return SLiib_Security_Checker_Abstract
     */
    protected final function _addPattern($pattern, $type, $locations)
    {
        if (!is_array($locations)) {
            $locations = array($locations);
        }

        $patternObj = new stdClass;

        $patternObj->str       = $pattern;
        $patternObj->type      = $type;
        $patternObj->locations = $locations;

        $this->_patterns[] = $patternObj;
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
    private function _check($pattern, $string)
    {
        if (preg_match('/' . $pattern . '/', $string)) {
            return FALSE;
        }

        return TRUE;

    }


    /**
     * Check in parameters
     *
     * @param string $pattern Pattern to check
     *
     * @return boolean
     */
    private final function _checkParameters($pattern)
    {
        $params = $this->_request->getParameters();

        foreach ($params as $key => $value) {
            if (!$this->_check($pattern, $key)) {
                return FALSE;
            }

            if (!$this->_check($pattern, $value)) {
                return FALSE;
            }
        }

        return TRUE;

    }


    /**
     * Check in user agent
     *
     * @param string $pattern Pattern to check
     *
     * @return boolean
     */
    private final function _checkUserAgent($pattern)
    {
        $userAgent = $this->_request->getUserAgent();
        return $this->_check($pattern, $userAgent);

    }


    /**
     * Check in http method
     *
     * @param string $pattern Pattern to check
     *
     * @return boolean
     */
    private final function _checkMethod($pattern)
    {
        $method = $this->_request->getRequestMethod();
        return $this->_check($pattern, $method);

    }


    /**
     * Check in cookies
     *
     * @param string $pattern Pattern to check
     *
     * @return boolean
     */
    private final function _checkCookies($pattern)
    {
        $cookies = $this->_request->getCookies();
        return $this->_check($pattern, $cookies);

    }


    /**
     * Check in referer
     *
     * @param string $pattern Pattern to check
     *
     * @return boolean
     */
    private final function _checkReferer($pattern)
    {
        $referer = $this->_request->getReferer();
        return $this->_check($pattern, $cookies);

    }


    //TODO Excluded pattern
    //TODO Exclude location (all for default)
    //TODO Checker extension file allowed


}