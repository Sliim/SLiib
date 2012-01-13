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
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\SystemInfos\Interfaces;

/**
 * \SLiib\SystemInfos\Interfaces\IApache2
 *
 * @package    SLiib\SystemInfos
 * @subpackage Interfaces
 */
Interface IApache2
{

    /**
     * Apache version
     * @const \string
     */
    const CMD_APACHE2_VERSION = 'apache2 -v';

    /**
     * Apache settings
     * @const \string
     */
    const CMD_APACHE2_SETTINGS = 'apache2 -V';

    /**
     * Compiled
     * @const \string
     */
    const CMD_APACHE2_COMPILED_MODULES = 'apache2 -l';

    /**
     * Directive configuration
     * @const \string
     */
    const CMD_APACHE2_DIRECTIVES_CONFIG = 'apache2 -L';

    /**
     * Parsed settings
     * @const \string
     */
    const CMD_APACHE2_PARSED_SETTINGS = 'apache2 -S';

    /**
     * Loaded modules
     * @const \string
     */
    const CMD_APACHE2_LOADED_MODULES = 'apache2 -M';

}

