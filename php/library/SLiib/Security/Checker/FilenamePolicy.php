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
 * @subpackage SLiib_Security_Checker
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Checker_FilenamePolicy
 *
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Checker
 */
class SLiib_Security_Checker_FilenamePolicy
extends SLiib_Security_Abstract_NegativeSecurityModel
{


    /**
     * Checker construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->_setName('Filename Policy');

        $fileExtensionRule = new SLiib_Security_Rule(
            1300,
            'Forbidden file\'s extension policy'
        );

        $fileExtensionRule->addPatternElement(
            array(
             '\.backup', '\.bak', '\.bat', '\.cfg', '\.cmd', '\.config', '\.conf', '\.dat', '\.db',
             '\.inc', '\.ini', '\.lnk', '\.log', '\.old', '\.pass', '\.pwd', '\.sql', '\.xml',
             '\.xsd', '\.xsx',
            )
        )->addLocation(self::LOCATION_REQUEST_URI);

        $fileNameRule = new SLiib_Security_Rule(
            1301,
            'Forbidden file\'s name policy'
        );
        $fileNameRule->addPatternElement(
            array(
             '\/etc\/passwd',
             '\/etc\/group',
             '\/etc\/shadow',
            )
        )->addLocation(self::LOCATION_REQUEST_URI);

        $this->addRule($fileExtensionRule)
            ->addRule($fileNameRule);

    }


}
