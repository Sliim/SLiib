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
     * Running checker
     *
     * @throws SLiib_Security_Exception_CheckerError
     * @throws SLiib_Security_Exception_HackingAttempt
     *
     * @return void
     */
    public final function run()
    {
        foreach ($this->_patterns as $pattern) {
            foreach ($pattern->locations as $location) {
                $attempt = true;
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
     * Check in parameters
     *
     * @param string $pattern Pattern to check
     *
     * @return boolean
     */
    private final function _checkParameters($pattern)
    {
        $params = SLiib_HTTP_Request::getParameters();

        foreach ($params as $key => $value) {
            if (preg_match('/' . preg_quote($pattern, '/') . '/', $key)) {
                return false;
            }

            if (preg_match('/' . preg_quote($pattern, '/') . '/', $value)) {
                return false;
            }
        }

        return true;

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
        $controller = SLiib_HTTP_Request::getUserAgent();

        if (preg_match('/' . preg_quote($pattern, '/') . '/', $controller)) {
            return false;
        }

        return true;

    }


    /**
     * Check in http method
     *
     * @param string $pattern Pattern to check
     *
     * @return boolean
     */
    private final function _checkAction($pattern)
    {
        $action = SLiib_HTTP_Request::getRequestMethod();

        if (preg_match('/' . preg_quote($pattern, '/') . '/', $action)) {
            return false;
        }

        return true;

    }

    //TODO Excluded pattern
    //TODO Exclude location (all for default)


}