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
 * SLiib_SystemInfos_Interfaces_IUname
 *
 * @package    SLiib_SystemInfos
 * @subpackage SLiib_SystemInfos_Interfaces
 */
Interface SLiib_SystemInfos_Interfaces_IUname
{

    /** Commande d'affichage du nom du noyau */
    const CMD_UNAME_KERNEL_NAME = 'uname -s';
    /** Affiche le nom d'host */
    const CMD_UNAME_HOSTNAME = 'uname -n';
    /** Affiche la release du noyau */
    const CMD_UNAME_KERNEL_RELEASE = 'uname -r';
    /** Affiche la version du noyau */
    const CMD_UNAME_KERNEL_VERSION = 'uname -v';
    /** Affiche le type d'architecture de l'host */
    const CMD_UNAME_ARCH = 'uname -m';
    /** Affiche le type de processeur */
    const CMD_UNAME_PROC = 'uname -p';
    /** Affiche les informations dur l'OS */
    const CMD_UNAME_OS_INFOS = 'uname -o';

}
