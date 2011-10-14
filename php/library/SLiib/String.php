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
 * @package  SLiib_String
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.1.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * Traitement de chaine de caractères
 * 
 * @package SLiib_String
 */
class SLiib_String
{


  /**
   * Supprime les espaces indésirables
   * 
   * @param string $string Chaine de caractères à nettoyer.
   * 
   * @return string La chaine nettoyée
   */
  static public function clean($string)
  {
    //Delete tabulation
    $count = 1;
    while ($count != 0)
      $string = str_replace("\t", ' ', $string, $count);

    //Delete double space into the string
    $count = 1;
    while ($count != 0)
      $string = str_replace('  ', ' ', $string, $count);

    //Delete space around the string
    $string = trim($string);

    return $string;

  }


}
