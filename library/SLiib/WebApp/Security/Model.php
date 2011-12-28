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
 * @package    SLiib_WebApp_Security
 * @subpackage Abstract
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_WebApp_Security_Abstract
 *
 * @package    SLiib_WebApp_Security
 * @subpackage Abstract
 */
abstract class SLiib_WebApp_Security_Abstract
{

    /**
     * Location contantes
     *
     * @var string
     */
    const LOCATION_REQUEST_URI = 'Request URI';
    const LOCATION_PARAMETERS  = 'Parameters';
    const LOCATION_USERAGENT   = 'UserAgent';
    const LOCATION_HTTP_METHOD = 'HTTP Method Request';
    const LOCATION_COOKIES     = 'Cookies';
    const LOCATION_REFERER     = 'Referer';

    /**
     * Security model
     */
    const MODEL_NEGATIVE = 'Negative';
    const MODEL_POSITIVE = 'Positive';

    /**
     * Security model
     * @var string
     */
    protected $_model = NULL;

    /**
     * Last pattern error
     * @var string
     */
    protected $_patternError;

    /**
     * Checker name
     * @var string
     */
    private $_name;

    /**
     * List of rules
     * @var array
     */
    private $_rules = array();

    /**
     * Request object
     * @var SLiib_WebApp_Request
     */
    private $_request;


    /**
     * Construct
     *
     * @throws SLiib_WebApp_Security_Exception
     *
     * @return void
     */
    public function __construct()
    {
        if (is_null($this->_model)) {
            throw new SLiib_WebApp_Security_Exception(
                'Security model undefined'
            );
        }

        if (!in_array($this->_model, array('Positive', 'Negative'))) {
            throw new SLiib_WebApp_Security_Exception(
                'Security model `' . $this->_model . '` invalid'
            );
        }

    }


    /**
     * Running checker
     *
     * @throws SLiib_WebApp_Security_Exception_CheckerError
     * @throws SLiib_WebApp_Security_Exception_HackingAttempt
     *
     * @return boolean
     */
    public final function run()
    {
        $this->_request = SLiib_WebApp_Request::getInstance();

        foreach ($this->_rules as $rule) {
            foreach ($rule->getLocation() as $location) {
                switch ($location) {
                    case self::LOCATION_REQUEST_URI:
                        $result = $this->_checkRequestUri($rule);
                        break;
                    case self::LOCATION_PARAMETERS:
                        $result = $this->_checkParameters($rule);
                        break;
                    case self::LOCATION_USERAGENT:
                        $result = $this->_checkUserAgent($rule);
                        break;
                    case self::LOCATION_HTTP_METHOD:
                        $result = $this->_checkMethod($rule);
                        break;
                    case self::LOCATION_COOKIES:
                        $result = $this->_checkCookies($rule);
                        break;
                    case self::LOCATION_REFERER:
                        $result = $this->_checkReferer($rule);
                        break;
                    default:
                        throw new SLiib_WebApp_Security_Exception_CheckerError(
                            'Location for `' . $this->_name . '` checker is not valid'
                        );
                        break;
                }

                if (!$result) {
                    throw new SLiib_WebApp_Security_Exception_HackingAttempt(
                        $this->_name, $rule, $location, $this->_patternError
                    );
                }
            }
        }

        return TRUE;

    }


    /**
     * Add a rule
     *
     * @param SLiib_WebApp_Security_Rule $rule Rule to add
     *
     * @throws SLiib_WebApp_Security_Exception_CheckerError
     *
     * @return SLiib_WebApp_Security_Abstract
     */
    public final function addRule(SLiib_WebApp_Security_Rule $rule)
    {
        if ($this->_ruleExists($rule->getId())) {
            throw new SLiib_WebApp_Security_Exception_CheckerError(
                'Id ' . $rule->getId() . ' already used by another rule.'
            );
        }

        $this->_rules[$rule->getId()] = $rule;
        return $this;

    }


    /**
     * Delete a rule
     *
     * @param int $ruleId Rule id to delete
     *
     * @throws SLiib_WebApp_Security_Exception_CheckerError
     *
     * @return SLiib_WebApp_Security_Abstract
     */
    public function deleteRule($ruleId)
    {
        if (!$this->_ruleExists($ruleId)) {
            throw new SLiib_WebApp_Security_Exception_CheckerError(
                'Rule ' . $ruleId . ' does not exist.'
            );
        }

        unset($this->_rules[$ruleId]);
        return $this;

    }


    /**
     * Get a rule defined by an id
     *
     * @param int $ruleId Rule Id to get
     *
     * @throws SLiib_WebApp_Security_Exception_CheckerError
     *
     * @return SLiib_WebApp_Security_Rule
     */
    public function getRule($ruleId)
    {
        if (!$this->_ruleExists($ruleId)) {
            throw new SLiib_WebApp_Security_Exception_CheckerError(
                'Rule ' . $ruleId . ' does not exist.'
            );
        }

        return $this->_rules[$ruleId];

    }


    /**
     * Get all added rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->_rules;

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
     * Check a pattern in a string
     *
     * @param string $pattern Pattern to check
     * @param string $string  String to use
     *
     * @return boolean
     */
    abstract protected function _check($pattern, $string);


    /**
     * Check if a pattern exists
     *
     * @param int $ruleId Rule Id
     *
     * @return boolean
     */
    protected final function _ruleExists($ruleId)
    {
        return array_key_exists($ruleId, $this->_rules);

    }


    /**
     * Check in request uri
     *
     * @param SLiib_WebApp_Security_Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function _checkRequestUri($rule)
    {
        $requestUri = $this->_request->getRequestUri();
        return $this->_check($rule, $requestUri);

    }


    /**
     * Check in parameters
     *
     * @param SLiib_WebApp_Security_Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function _checkParameters($rule)
    {
        $params = $this->_request->getParameters();
        foreach ($params as $key => $value) {
            if (!$this->_check($rule, $key)) {
                return FALSE;
            }

            if (!$this->_check($rule, $value)) {
                return FALSE;
            }
        }

        return TRUE;

    }


    /**
     * Check in user agent
     *
     * @param SLiib_WebApp_Security_Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function _checkUserAgent($rule)
    {
        $userAgent = $this->_request->getUserAgent();
        return $this->_check($rule, $userAgent);

    }


    /**
     * Check in http method
     *
     * @param SLiib_WebApp_Security_Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function _checkMethod($rule)
    {
        $method = $this->_request->getRequestMethod();
        return $this->_check($rule, $method);

    }


    /**
     * Check in cookies
     *
     * @param SLiib_WebApp_Security_Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function _checkCookies($rule)
    {
        $cookies = $this->_request->getCookies();

        foreach ($cookies as $key => $value) {
            if (!$this->_check($rule, $key)) {
                return FALSE;
            }

            if (!$this->_check($rule, $value)) {
                return FALSE;
            }
        }

        return TRUE;

    }


    /**
     * Check in referer
     *
     * @param SLiib_WebApp_Security_Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function _checkReferer($rule)
    {
        $referer = $this->_request->getReferer();
        return $this->_check($rule, $referer);

    }


}