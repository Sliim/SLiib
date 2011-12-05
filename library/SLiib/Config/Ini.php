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
 * @package    SLiib_Config
 * @subpackage Ini
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Config_Ini
 *
 * @package    SLiib_Config
 * @subpackage Ini
 */
class SLiib_Config_Ini extends SLiib_Config
{


    /**
     * Parse le fichier de configuration
     *
     * @see SLiib_Config
     *
     * @throws SLiib_Config_Exception_SyntaxError
     *
     * @return void
     */
    protected function _parseFile()
    {
        set_error_handler(array($this, '_errorHandler'));

        $config = parse_ini_file($this->_configFile, TRUE, INI_SCANNER_RAW);

        restore_error_handler();

        if (!$config) {
            throw new SLiib_Config_Exception_SyntaxError(
                'Can\'t parse `' . $this->_configFile . '`'
            );
        }

        $this->_config = $this->_parseSection($config);

    }


    /**
     * Section parsing
     *
     * @param array $section Section to parse
     *
     * @throws SLiib_Config_Exception_SyntaxError
     *
     * @return stdClass
     */
    private function _parseSection($section)
    {
        $object = new stdClass();

        foreach ($section as $key => $value) {
            if (strpos($key, '.')) {
                $value = $this->_parseMultipleSection($key, $value);
            }

            if (is_array($value)) {
                if (preg_match('/:/', $key)) {
                    $segment = explode(':', $key);

                    if (count($segment) != 2) {
                        throw new SLiib_Config_Exception_SyntaxError(
                            'Section definition incorrect (' . $key . ')'
                        );
                    }

                    $key    = SLiib_String::clean($segment[0]);
                    $parent = SLiib_String::clean($segment[1]);

                    if (!isset($object->$parent)) {
                        throw new SLiib_Config_Exception_SyntaxError(
                            'Try to herite `' . $key . '` to `' . $parent .
                            '` but `' . $parent . '` does not exists.'
                        );
                    }
                }

                $object->$key = $this->_parseSection($value);
                if (isset($parent)) {
                    $object->$key = (object) array_merge(
                        (array) $object->$parent,
                        (array) $object->$key
                    );
                }
            } else {
                if (isset($object->$key)) {
                    $this->_mergeObject($object->$key, $value);
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
     * @return stdClass
     */
    private function _parseMultipleSection(&$key, $value)
    {
        $segment = explode('.', $key);
        $key     = array_shift($segment);
        $cSeg    = count($segment);
        $object  = new stdClass;
        $parent  = NULL;

        if ($cSeg === 1) {
            $object->{array_shift($segment)} = $value;
            return $object;
        }

        foreach ($segment as $k => $p) {
            if (is_null($parent)) {
                $object->$p = new stdClass;
                $parent     = $object->$p;
            } else {
                if ($k != $cSeg - 1) {
                    $parent->$p = new stdClass;
                    $parent     = $parent->$p;
                } else {
                    $parent->$p = $value;
                }
            }
        }

        return $object;

    }


    /**
     * Merge with an existing object config
     *
     * @param stdClass &$source Object source
     * @param stdClass $object  Object to merge with source
     *
     * @return void
     */
    private function _mergeObject(stdClass &$source, stdClass $object)
    {
        foreach ($object as $key => $value) {
            if (!isset($source->$key)) {
                $source->$key = $value;
                continue;
            }

            if (is_object($source->$key) && is_object($value)) {
                $this->_mergeObject($source->$key, $value);
            }
        }

    }


    /**
     * Error handler for syntax error
     *
     * @param int    $errno  Error level
     * @param string $errstr Error message
     *
     * @throws SLiib_Config_Exception_SyntaxError
     *
     * @return void
     */
    private function _errorHandler($errno, $errstr)
    {
        restore_error_handler();
        throw new SLiib_Config_Exception_SyntaxError('[' . $errno . ']' . $errstr);

    }


}
