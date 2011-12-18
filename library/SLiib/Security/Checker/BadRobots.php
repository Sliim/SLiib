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
 * @subpackage Checker
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Checker_BadRobots
 *
 * @package    SLiib_Security
 * @subpackage Checker
 */
class SLiib_Security_Checker_BadRobots
extends SLiib_Security_Abstract_NegativeSecurityModel
{


    /**
     * Checker construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->_setName('Bad Robots');

        $scanner = new SLiib_Security_Rule(
            1400,
            'Scanner detection'
        );

        $scanner->enablePregQuote()
            ->disableCaseSensitivity()
            ->addPatternElement(
                array(
                 'metis',
                 'bilbo',
                 'n-stealth',
                 'black widow',
                 'brutus',
                 'cgichk',
                 'webtrends security analyzer',
                 'mozilla/4.0 (compatible)',
                 'jaascois',
                 'pmafind',
                 '.nasl',
                 'nsauditor',
                 'paros',
                 'nessus',
                 'nikto',
                 'webinspect',
                 'blackwidow',
                 'dirbuster',
                 'w3af',
                 'python-httplib',
                 'grabber',
                 'grendel-scan',
                 'python-urllib',
                 'mozilla/5.0 sf',
                )
            )->addLocation(self::LOCATION_USERAGENT);

        $this->addRule($scanner);

    }


}
