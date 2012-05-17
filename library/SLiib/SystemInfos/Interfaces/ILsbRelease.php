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
 * @package    SLiib\SystemInfos
 * @subpackage Interfaces
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\SystemInfos\Interfaces;

/**
 * \SLiib\SystemInfos\Interfaces\ILsbRelease
 *
 * @package    SLiib\SystemInfos
 * @subpackage Interfaces
 */
Interface ILsbRelease
{

    /**
     * Distribution id
     * @const string
     */
    const CMD_LSB_RELEASE_ID = 'lsb_release -i';

    /**
     * Distribution description
     * @const string
     */
    const CMD_LSB_RELEASE_DESC = 'lsb_release -d';

    /**
     * Distribution release
     * @const string
     */
    const CMD_LSB_RELEASE_RELEASE = 'lsb_release -r';

    /**
     * Distribution codename
     * @const string
     */
    const CMD_LSB_RELEASE_CODENAME = 'lsb_release -c';

    /**
     * All infos
     * @const string
     */
    const CMD_LSB_RELEASE_ALL = 'lsb_release -idrc';

}
