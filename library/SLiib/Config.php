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
 * @package  SLiib\Config
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

use SLiib\Config\Exception as ConfigException;
use SLiib\Config\Exception\UndefinedProperty;

/**
 * \SLiib\Config
 *
 * @package SLiib\Config
 */
class Config
{

    /**
     * Configuration file
     * @var string
     */
    protected static $file = null;

    /**
     * Undefined property getter
     *
     * @param string $key Key to get
     *
     * @throws \SLiib\Config\Exception\UndefinedProperty
     *
     * @return void
     */
    public function __get($key)
    {
        throw new UndefinedProperty('Property `' . $key . '` undefined in config');
    }

    /**
     * Read a configuration file
     *
     * @param string $file File to read
     *
     * @throws \SLiib\Config\Exception
     *
     * @return \SLiib\Config
     */
    public static function read($file)
    {
        if (!file_exists($file)) {
            throw new ConfigException('File ' . $file . ' not found');
        }

        static::$file = $file;
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

