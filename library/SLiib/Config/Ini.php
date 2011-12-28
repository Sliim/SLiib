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
 * @package    SLiib\Config
 * @subpackage Ini
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * Namespace
 */
namespace SLiib\Config;

/**
 * Uses
 */
use SLiib\Config,
    SLiib\Config\Exception,
    SLiib\Utils,
    SLiib\String;

/**
 * SLiib\Config\Ini
 *
 * @package    SLiib\Config
 * @subpackage Ini
 */
class Ini extends \SLiib\Config
{


    /**
     * Read a configuration file
     *
     * @param string           $file File to read
     * @param string[optional] $env  Config environment
     *
     * @throws SLiib\Config\Exception\UndefinedProperty
     *
     * @return SLiib\Config
     */
    public static function read($file, $env=NULL)
    {
        parent::read($file, $env);

        $config = new self(TRUE);

        if (!is_null($env)) {
            if (!isset($config->$env)) {
                throw new Exception\UndefinedProperty(
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
     * @param boolean[optional] $init Specify if is a config object init
     *
     * @return void
     */
    protected function __construct($init=FALSE)
    {
        parent::__construct();

        if ($init) {
            $this->_parseFile();
        }

    }


    /**
     * Parse le fichier de configuration
     *
     * @throws SLiib\Config\Exception\SyntaxError
     *
     * @return void
     */
    protected function _parseFile()
    {
        set_error_handler(array($this, '_errorHandler'));

        $config = parse_ini_file(static::$_file, TRUE, INI_SCANNER_RAW);

        restore_error_handler();

        if (!$config) {
            throw new Exception\SyntaxError('Can\'t parse `' . static::$_file . '`');
        }

        Utils::mergeObject($this, $this->_parseSection($config));

    }


    /**
     * Section parsing
     *
     * @param array $section Section to parse
     *
     * @throws SLiib\Config\Exception\SyntaxError
     *
     * @return SLiib\Config
     */
    private function _parseSection($section)
    {
        $object = new Config();

        foreach ($section as $key => $value) {
            if (strpos($key, '.')) {
                $value = $this->_parseMultipleSection($key, $value);
            }

            if (is_array($value)) {
                if (preg_match('/:/', $key)) {
                    $segment = explode(':', $key);

                    if (count($segment) != 2) {
                        throw new Exception\SyntaxError(
                            'Section definition incorrect (' . $key . ')'
                        );
                    }

                    $key    = String::clean($segment[0]);
                    $parent = String::clean($segment[1]);

                    if (!isset($object->$parent)) {
                        throw new Exception\SyntaxError(
                            'Try to herite `' . $key . '` to `' . $parent .
                            '` but `' . $parent . '` does not exists.'
                        );
                    }
                }

                $object->$key = $this->_parseSection($value);

                if (isset($parent)) {
                    Utils::mergeObject($object->$key, $object->$parent);
                }
            } else {
                if (isset($object->$key)) {
                    Utils::mergeObject($object->$key, $value);
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
     * @return SLiib\Config
     */
    private function _parseMultipleSection(&$key, $value)
    {
        $segment = explode('.', $key);
        $key     = array_shift($segment);
        $cSeg    = count($segment);
        $object  = new Config();
        $parent  = NULL;

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
     * @throws SLiib\Config\Exception\SyntaxError
     *
     * @return void
     */
    private function _errorHandler($errno, $errstr)
    {
        restore_error_handler();
        throw new Exception\SyntaxError('[' . $errno . ']' . $errstr);

    }


}
