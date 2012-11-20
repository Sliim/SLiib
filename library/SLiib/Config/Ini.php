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
 * @package    SLiib\Config
 * @subpackage Ini
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace SLiib\Config;

use SLiib\Config;
use SLiib\Utils;
use SLiib\String;
use SLiib\Config\Exception\UndefinedProperty;
use SLiib\Config\Exception\SyntaxError;

/**
 * \SLiib\Config\Ini
 *
 * @package    SLiib\Config
 * @subpackage Ini
 */
class Ini extends Config
{
    /**
     * Read a configuration file
     *
     * @param string $file File to read
     * @param string $env  Config environment
     *
     * @throws UndefinedProperty
     *
     * @return \SLiib\Config
     */
    public static function read($file, $env = null)
    {
        parent::read($file, $env);

        $config = new self(true);

        if (!is_null($env)) {
            if (!isset($config->$env)) {
                throw new UndefinedProperty(
                    'Environment `' . $env . '` does not exist.'
                );
            }

            return $config->$env;
        }

        return $config;
    }

    /**
     * Protected constructor
     *
     * @param boolean $init Specify if is a config object init
     *
     * @return void
     */
    protected function __construct($init = false)
    {
        parent::__construct();

        if ($init) {
            $this->parseFile();
        }
    }

    /**
     * Parse le fichier de configuration
     *
     * @throws SyntaxError
     *
     * @return void
     */
    protected function parseFile()
    {
        set_error_handler(array($this, 'errorHandler'));

        $config = parse_ini_file(static::$file, true, INI_SCANNER_RAW);

        restore_error_handler();

        if (!$config) {
            throw new SyntaxError('Can\'t parse `' . static::$file . '`');
        }

        Utils\Object::merge($this, $this->parseSection($config));
    }

    /**
     * Section parsing
     *
     * @param array $section Section to parse
     *
     * @throws SyntaxError
     *
     * @return \SLiib\Config
     */
    private function parseSection(array $section)
    {
        $object = new Config();

        foreach ($section as $key => $value) {
            if (strpos($key, '.')) {
                $value = $this->parseMultipleSection($key, $value);
            }

            if (is_array($value)) {
                if (preg_match('/:/', $key)) {
                    $segment = explode(':', $key);

                    if (count($segment) != 2) {
                        throw new SyntaxError(
                            'Section definition incorrect (' . $key . ')'
                        );
                    }

                    $key    = Utils\String::clean($segment[0]);
                    $parent = Utils\String::clean($segment[1]);

                    if (!isset($object->$parent)) {
                        throw new SyntaxError(
                            'Try to herite `' . $key . '` to `' . $parent .
                            '` but `' . $parent . '` does not exists.'
                        );
                    }
                }

                $object->$key = $this->parseSection($value);

                if (isset($parent)) {
                    Utils\Object::merge($object->$key, $object->$parent);
                }
            } else {
                if (isset($object->$key)) {
                    Utils\Object::merge($object->$key, $value);
                } else {
                    $object->$key = $value;
                }
            }
        }

        return $object;
    }

    /**
     * Parse multiple section defined in a key
     * for example : foo.bar.baz = w00t
     * result : $object->foo->bar->baz = w00t
     *
     * @param string &$key  Multiple Key
     * @param mixed  $value Origin value
     *
     * @return \SLiib\Config
     */
    private function parseMultipleSection(&$key, $value)
    {
        $segment = explode('.', $key);
        $key     = array_shift($segment);
        $cSeg    = count($segment);
        $object  = new Config();
        $parent  = null;

        if ($cSeg === 1) {
            $object->{array_shift($segment)} = $value;
            return $object;
        }

        foreach ($segment as $k => $p) {
            if (is_null($parent)) {
                $object->$p = new Config();
                $parent     = $object->$p;
            } else {
                if ($k != $cSeg - 1) {
                    $parent->$p = new Config();
                    $parent     = $parent->$p;
                } else {
                    $parent->$p = $value;
                }
            }
        }

        return $object;
    }

    /**
     * Error handler for syntax error
     *
     * @param int    $errno  Error level
     * @param string $errstr Error message
     *
     * @throws SyntaxError
     *
     * @return void
     */
    private function errorHandler($errno, $errstr)
    {
        restore_error_handler();
        throw new SyntaxError('[' . $errno . ']' . $errstr);
    }
}
