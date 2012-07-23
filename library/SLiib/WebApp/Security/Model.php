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

use SLiib\WebApp\Security\Exception\CheckerError;
use SLiib\WebApp\Security\Exception\HackingAttempt;
use SLiib\WebApp\Security\Model\Exception as ModelException;
use SLiib\WebApp\Request;

/**
 * \SLiib\WebApp\Security\Model
 *
 * @package    SLiib\WebApp
 * @subpackage Security
 */
abstract class Model
{
    const MODEL_NEGATIVE = 'Negative';
    const MODEL_POSITIVE = 'Positive';

    /**
     * Security model
     * @var string
     */
    protected $_model = null;

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
     * Model's rules list
     * @var array
     */
    private $_rules = array();

    /**
     * Request object
     * @var \SLiib\WebApp\Request
     */
    private $_request;

    /**
     * Construct
     *
     * @throws \SLiib\WebApp\Security\Exception
     *
     * @return void
     */
    public function __construct()
    {
        if (is_null($this->_model)) {
            throw new ModelException('Security model undefined');
        }

        if (!in_array($this->_model, array('Positive', 'Negative'))) {
            throw new ModelException('Security model `' . $this->_model . '` invalid');
        }
    }

    /**
     * Running checker
     *
     * @throws \SLiib\WebApp\Security\Exception\CheckerError
     * @throws \SLiib\WebApp\Security\Exception\HackingAttempt
     *
     * @return boolean
     */
    public final function run()
    {
        $this->_request = Request::getInstance();

        foreach ($this->_rules as $rule) {
            foreach ($rule->getLocation() as $location) {
                switch ($location) {
                    case Rule::LOCATION_REQUEST_URI:
                        $result = $this->checkRequestUri($rule);
                        break;
                    case Rule::LOCATION_PARAMETERS:
                        $result = $this->checkParameters($rule);
                        break;
                    case Rule::LOCATION_USERAGENT:
                        $result = $this->checkUserAgent($rule);
                        break;
                    case Rule::LOCATION_HTTP_METHOD:
                        $result = $this->checkMethod($rule);
                        break;
                    case Rule::LOCATION_COOKIES:
                        $result = $this->checkCookies($rule);
                        break;
                    case Rule::LOCATION_REFERER:
                        $result = $this->checkReferer($rule);
                        break;
                    default:
                        throw new CheckerError(
                            'Location for `' . $this->_name . '` checker is not valid'
                        );
                        break;
                }

                if (!$result) {
                    throw new HackingAttempt(
                        $this->_name, $rule, $location, $this->_patternError
                    );
                }
            }
        }

        return true;
    }

    /**
     * Add a rule
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to add
     *
     * @throws \SLiib\WebApp\Security\Exception\CheckerError
     *
     * @return \SLiib\WebApp\Security\Model
     */
    public final function addRule(Rule $rule)
    {
        if ($this->ruleExists($rule->getId())) {
            throw new CheckerError(
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
     * @throws \SLiib\WebApp\Security\Exception\CheckerError
     *
     * @return \SLiib\WebApp\Security\Model
     */
    public function deleteRule($ruleId)
    {
        if (!$this->ruleExists($ruleId)) {
            throw new CheckerError('Rule ' . $ruleId . ' does not exist.');
        }

        unset($this->_rules[$ruleId]);
        return $this;
    }

    /**
     * Get a rule defined by an id
     *
     * @param int $ruleId Rule Id to get
     *
     * @throws \SLiib\WebApp\Security\Exception\CheckerError
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function getRule($ruleId)
    {
        if (!$this->ruleExists($ruleId)) {
            throw new CheckerError('Rule ' . $ruleId . ' does not exist.');
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
    protected final function setName($name)
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
    abstract protected function check($pattern, $string);

    /**
     * Check if a pattern exists
     *
     * @param int $ruleId Rule Id
     *
     * @return boolean
     */
    protected final function ruleExists($ruleId)
    {
        return array_key_exists($ruleId, $this->_rules);
    }

    /**
     * Check in request uri
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function checkRequestUri(Rule $rule)
    {
        $requestUri = $this->_request->getRequestUri();
        return $this->check($rule, $requestUri);
    }

    /**
     * Check in parameters
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function checkParameters(Rule $rule)
    {
        $params = $this->_request->getParameters();
        foreach ($params as $key => $value) {
            if (!$this->check($rule, $key)) {
                return false;
            }

            if (!$this->check($rule, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check in user agent
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function checkUserAgent(Rule $rule)
    {
        $userAgent = $this->_request->getUserAgent();
        return $this->check($rule, $userAgent);
    }

    /**
     * Check in http method
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function checkMethod(Rule $rule)
    {
        $method = $this->_request->getRequestMethod();
        return $this->check($rule, $method);
    }

    /**
     * Check in cookies
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function checkCookies(Rule $rule)
    {
        $cookies = $this->_request->getCookies();

        foreach ($cookies as $key => $value) {
            if (!$this->check($rule, $key)) {
                return false;
            }

            if (!$this->check($rule, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check in referer
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    private final function checkReferer(Rule $rule)
    {
        $referer = $this->_request->getReferer();
        return $this->check($rule, $referer);
    }
}

