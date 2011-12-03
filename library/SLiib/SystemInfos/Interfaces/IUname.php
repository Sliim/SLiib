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
 * @subpackage Interfaces
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_SystemInfos_Interfaces_IUname
 *
 * @package    SLiib_SystemInfos
 * @subpackage Interfaces
 */
Interface SLiib_SystemInfos_Interfaces_IUname
{

    /**
     * Kernel name
     * @var string
     */
    const CMD_UNAME_KERNEL_NAME = 'uname -s';

    /**
     * Hostname
     * @var string
     */
    const CMD_UNAME_HOSTNAME = 'uname -n';

    /**
     * Kernel release
     * @var string
     */
    const CMD_UNAME_KERNEL_RELEASE = 'uname -r';

    /**
     * Kernel version
     * @var string
     */
    const CMD_UNAME_KERNEL_VERSION = 'uname -v';

    /**
     * Architecture
     * @var string
     */
    const CMD_UNAME_ARCH = 'uname -m';

    /**
     * Proc
     * @var string
     */
    const CMD_UNAME_PROC = 'uname -p';

    /**
     * OS Infos
     * @var string
     */
    const CMD_UNAME_OS_INFOS = 'uname -o';

}
