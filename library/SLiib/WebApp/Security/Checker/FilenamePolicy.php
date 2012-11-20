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
 * \SLiib\WebApp\Security\Checker\FilenamePolicy
 *
 * @package    SLiib\WebApp
 * @subpackage Security\Checker
 */
class FilenamePolicy extends NegativeSecurity
{
    /**
     * Checker construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->setName('Filename Policy');

        $fileExtensionRule = new Rule(1300, 'Forbidden file\'s extension policy');
        $fileExtensionRule->addPatternElement(
            array(
             '\.backup', '\.bak', '\.bat', '\.cfg', '\.cmd', '\.config', '\.conf', '\.dat', '\.db',
             '\.inc', '\.ini', '\.lnk', '\.log', '\.old', '\.pass', '\.pwd', '\.sql', '\.xml',
             '\.xsd', '\.xsx',
            )
        )->addLocation(Rule::LOCATION_REQUEST_URI);

        $fileNameRule = new Rule(1301, 'Forbidden file\'s name policy');
        $fileNameRule->addPatternElement(
            array(
             '\/etc\/passwd',
             '\/etc\/group',
             '\/etc\/shadow',
            )
        )->addLocation(Rule::LOCATION_REQUEST_URI);

        $this->addRule($fileExtensionRule)
            ->addRule($fileNameRule);
    }
}
