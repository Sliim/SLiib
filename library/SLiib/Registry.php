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
 * @category SLiib
 * @package  SLiib\Registry
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\Registry
 *
 * @package SLiib\Registry
 */
class Registry
{

    /**
     * Tableau contenant le registre
     * @var \array
     */
    private static $_registry = array();


    /**
     * Récupère une valeur dans le registre à partir d'une clé.
     *
     * @param \string $key Key à récupérer
     *
     * @throws Registry\Exception
     *
     * @return \mixed Valeur de la clé.
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$_registry)) {
            throw new Registry\Exception('Key ' . $key . ' not found in registry.');
        }

        return self::$_registry[$key];

    }


    /**
     * Définit une valeur dans le registre
     *
     * @param \string $key   Key à définir
     * @param \mixed  $value Valeur à affecter
     *
     * @throws Registry\Exception
     *
     * @return \void
     */
    public static function set($key, $value)
    {
        if (array_key_exists($key, self::$_registry)) {
            throw new Registry\Exception('Key ' . $key . ' already exist.');
        }

        self::$_registry[$key] = $value;

    }


}
