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
 * \SLiib\SystemInfos\Interfaces\IPhp
 *
 * @package    SLiib\SystemInfos
 * @subpackage Interfaces
 */
Interface IPhp
{

    /**
     * PHP version
     * @const \string
     */
    const CMD_PHP_VERSION = 'php -v';

    /**
     * PHP infos
     * @const \string
     */
    const CMD_PHP_INFOS = 'php -i';

    /**
     * Modules
     * @const \string
     */
    const CMD_PHP_MODULES = 'php -m';

    /**
     * Ini
     * @const \string
     */
    const CMD_PHP_INI = 'php --ini';

}
