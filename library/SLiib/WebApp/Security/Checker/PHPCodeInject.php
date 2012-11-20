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
 * @subpackage Security\Checker
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\WebApp\Security\Checker;

use SLiib\WebApp\Security\Model\NegativeSecurity;
use SLiib\WebApp\Security\Rule;

/**
 * \SLiib\WebApp\Security\Checker\PHPCodeInject
 *
 * @package    SLiib\WebApp
 * @subpackage Security\Checker
 */
class PHPCodeInject extends NegativeSecurity
{
    /**
     * Checker construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->setName('PHP Code Injection');

        $includeRule = new Rule(1100, 'Include injection detected');
        $includeRule->addPatternElement(
            array(
             'include(_once)?[\( ]?[\'\"]{1}(.+)[\'\"]{1}[\)]?',
             'require(_once)?[\( ]?[\'\"]{1}(.+)[\'\"]{1}[\)]?',
            )
        )->addLocation(
            array(
             Rule::LOCATION_PARAMETERS,
             Rule::LOCATION_USERAGENT,
            )
        );

        $othersFunctionRule = new Rule(1101, 'Others functions injection detected');
        $othersFunctionRule->addPatternElement(
            array(
             'file_get_contents\((.*)\)',
             'eval\((.*)\)',
            )
        )->addLocation(
            array(
             Rule::LOCATION_PARAMETERS,
             Rule::LOCATION_USERAGENT,
            )
        );

        $remoteExecRule = new Rule(1102, 'Remote commande execution detected');
        $remoteExecRule->addPatternElement(
            array(
             'exec\((.*)\)',
             'passthru\((.*)\)',
             'proc_open\((.*)\)',
             'shell_exec\((.*)\)',
             'system\((.*)\)',
            )
        )->addLocation(
            array(
             Rule::LOCATION_PARAMETERS,
             Rule::LOCATION_USERAGENT,
            )
        );

        $this->addRule($includeRule)
            ->addRule($othersFunctionRule)
            ->addRule($remoteExecRule);
    }
}
