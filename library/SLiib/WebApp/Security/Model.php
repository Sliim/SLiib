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
    protected $model = null;

    /**
     * Last pattern error
     * @var string
     */
    protected $patternError;

    /**
     * Checker name
     * @var string
     */
    private $name;

    /**
     * Model's rules list
     * @var array
     */
    private $rules = array();

    /**
     * Request object
     * @var \SLiib\WebApp\Request
     */
    private $request;

    /**
     * Construct
     *
     * @throws ModelException
     *
     * @return void
     */
    public function __construct()
    {
        if (is_null($this->model)) {
            throw new ModelException('Security model undefined');
        }

        if (!in_array($this->model, array('Positive', 'Negative'))) {
            throw new ModelException('Security model `' . $this->model . '` invalid');
        }
    }

    /**
     * Running checker
     *
     * @throws CheckerError
     * @throws HackingAttempt
     *
     * @return boolean
     */
    final public function run()
    {
        $this->request = Request::getInstance();

        foreach ($this->rules as $rule) {
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
                        throw new CheckerError('Location for `' . $this->name . '` is invalid');
                }

                if (!$result) {
                    throw new HackingAttempt(
                        $this->name,
                        $rule,
                        $location,
                        $this->patternError
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
     * @throws CheckerError
     *
     * @return \SLiib\WebApp\Security\Model
     */
    final public function addRule(Rule $rule)
    {
        if ($this->ruleExists($rule->getId())) {
            throw new CheckerError(
                'Id ' . $rule->getId() . ' already used by another rule.'
            );
        }

        $this->rules[$rule->getId()] = $rule;
        return $this;
    }

    /**
     * Delete a rule
     *
     * @param int $ruleId Rule id to delete
     *
     * @throws CheckerError
     *
     * @return \SLiib\WebApp\Security\Model
     */
    public function deleteRule($ruleId)
    {
        if (!$this->ruleExists($ruleId)) {
            throw new CheckerError('Rule ' . $ruleId . ' does not exist.');
        }

        unset($this->rules[$ruleId]);
        return $this;
    }

    /**
     * Get a rule defined by an id
     *
     * @param int $ruleId Rule Id to get
     *
     * @throws CheckerError
     *
     * @return \SLiib\WebApp\Security\Rule
     */
    public function getRule($ruleId)
    {
        if (!$this->ruleExists($ruleId)) {
            throw new CheckerError('Rule ' . $ruleId . ' does not exist.');
        }

        return $this->rules[$ruleId];
    }

    /**
     * Get all added rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;

    }

    /**
     * Set checker name
     *
     * @param string $name Checker name
     *
     * @return void
     */
    final protected function setName($name)
    {
        $this->name = $name;
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
    final protected function ruleExists($ruleId)
    {
        return array_key_exists($ruleId, $this->rules);
    }

    /**
     * Check in request uri
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    final private function checkRequestUri(Rule $rule)
    {
        $requestUri = $this->request->getRequestUri();
        return $this->check($rule, $requestUri);
    }

    /**
     * Check in parameters
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    final private function checkParameters(Rule $rule)
    {
        $params = $this->request->getParameters();
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
    final private function checkUserAgent(Rule $rule)
    {
        $userAgent = $this->request->getUserAgent();
        return $this->check($rule, $userAgent);
    }

    /**
     * Check in http method
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    final private function checkMethod(Rule $rule)
    {
        $method = $this->request->getRequestMethod();
        return $this->check($rule, $method);
    }

    /**
     * Check in cookies
     *
     * @param \SLiib\WebApp\Security\Rule $rule Rule to check
     *
     * @return boolean
     */
    final private function checkCookies(Rule $rule)
    {
        $cookies = $this->request->getCookies();

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
    final private function checkReferer(Rule $rule)
    {
        $referer = $this->request->getReferer();
        return $this->check($rule, $referer);
    }
}
