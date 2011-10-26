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
 * @package    SLiib_SystemInfos
 * @subpackage SLiib_SystemInfos_Interfaces
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_SystemInfos_Interfaces_IApache2
 *
 * @package    SLiib_SystemInfos
 * @subpackage SLiib_SystemInfos_Interfaces
 */
Interface SLiib_SystemInfos_Interfaces_IApache2
{

    const CMD_APACHE2_VERSION           = 'apache2 -v';
    const CMD_APACHE2_SETTINGS          = 'apache2 -V';
    const CMD_APACHE2_COMPILED_MODULES  = 'apache2 -l';
    const CMD_APACHE2_DIRECTIVES_CONFIG = 'apache2 -L';
    const CMD_APACHE2_PARSED_SETTINGS   = 'apache2 -S';
    const CMD_APACHE2_LOADED_MODULES    = 'apache2 -M';

}

