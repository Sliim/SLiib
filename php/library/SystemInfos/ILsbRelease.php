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
 * @category SLiib
 * @package  Library
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.1
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_SystemInfos_ILsbRelease
 * 
 * @package    SystemInfos
 * @subpackage Interface
 */
Interface SLiib_SystemInfos_ILsbRelease
{

  /** ID du distributeur */
  const CMD_LSB_RELEASE_ID = 'lsb_release -i';
  /** Description de la distribution*/
  const CMD_LSB_RELEASE_DESC = 'lsb_release -d';
  /** Release de la distribution */
  const CMD_LSB_RELEASE_RELEASE = 'lsb_release -r';
  /** Nom de code de la distribution */
  const CMD_LSB_RELEASE_CODENAME = 'lsb_release -c';
  /** Toute les informations ci-dessus */
  const CMD_LSB_RELEASE_ALL = 'lsb_release -idrc';

}
