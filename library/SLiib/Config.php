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
 * @package  SLiib_Config
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_Config
 *
 * @package SLiib_Config
 */
class SLiib_Config
{

    /**
     * Configuration file
     * @var string
     */
    protected static $_file = NULL;


    /**
     * Undefined property getter
     *
     * @param string $key Key to get
     *
     * @throws SLiib_Config_Exception_UndefinedProperty
     *
     * @return void
     */
    public function __get($key)
    {
        throw new SLiib_Config_Exception_UndefinedProperty(
            'Property `' . $key . '` undefined in config'
        );

    }


    /**
     * Read a configuration file
     *
     * @param string           $file File to read
     * @param string[optional] $env  Config environment
     *
     * @throws SLiib_Config_Exception
     *
     * @return SLiib_Config
     */
    public static function read($file, $env=NULL)
    {
        if (!file_exists($file)) {
            throw new SLiib_Config_Exception('File ' . $file . ' not found');
        }

        static::$_file = $file;

    }


    /**
     * Protected constructor
     *
     * @return void
     */
    protected function __construct()
    {

    }


}
