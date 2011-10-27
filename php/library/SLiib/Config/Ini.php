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
 * @subpackage SLiib_Config_Ini
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Config_Ini
 *
 * @package    SLiib_Config
 * @subpackage SLiib_Config_Ini
 */
class SLiib_Config_Ini extends SLiib_Config
{


    /**
     * Lit le fichier de configuration
     *
     * @see SLiib_Config
     *
     * @throws SLiib_Config_Exception
     *
     * @return int Nombre de ligne lue
     */
    protected function _readFile()
    {
        $fp = fopen($this->_configFile, 'r');

        $this->_pointer = 0;
        $section        = false;
        while ($line = fgets($fp, 256)) {
            $this->_pointer++;
            $line = SLiib_String::clean($line);
            if (!empty($line)) {
                switch ($line[0]) {
                    case ';':
                        break;
                    case '[':
                        $section = $this->_initSection($line);
                        break;
                    default:
                        $this->_initParam($line, $section);
                        break;
                }
            }
        }

        fclose($fp);

        return $this->_pointer;

    }


    /**
     * Init d'une section du fichier
     *
     * @param string $section Section definition
     *
     * @throws SLiib_Config_Exception_SyntaxError
     *
     * @return string Section name
     */
    private function _initSection($section)
    {
        //TODO afficher fichier concerné dans le message.
        if (preg_match('/ : /', $section)) {
            $segment = explode(' : ', $section);

            if (count($segment) != 2) {
                throw new SLiib_Config_Exception_SyntaxError(
                    'Section definition incorrect at line ' . $this->_pointer
                );
            }

            $section = SLiib_String::clean(str_replace('[', '', $segment[0]));
            $parent  = SLiib_String::clean(str_replace(']', '', $segment[1]));

            if (!isset($this->_config->$parent)) {
                throw new SLiib_Config_Exception_SyntaxError(
                    '`' . $parent . '` undefined in `' . $this->_configFile .
                    '` at line ' . $this->_pointer
                );
            }

            $this->_config->$section = clone $this->_config->$parent;
        } else {
            $section = str_replace(array('[', ']'), '', $section);

            $this->_config->$section = new stdClass;
        }

        return $section;

    }


    /**
     * Init un paramètre du fichier
     *
     * @param string $param   Paramètre à initialiser
     * @param string $section Section concernée
     *
     * @return void
     */
    private function _initParam($param, $section=false)
    {
        //TODO si ya pas de = ==> syntaxerror
        $datas = explode('=', $param);

        if (count($datas) != 2) {
            throw new SLiib_Config_Exception_SyntaxError(
                'Directive declaration incorrect at line ' . $this->_pointer
            );
        }

        $key   = SLiib_String::clean($datas[0]);
        $value = SLiib_String::clean($datas[1]);

        if (strpos($key, ' ')) {
            throw new SLiib_Config_Exception_SyntaxError(
                'Directive name should not be spaces at line ' . $this->_pointer
            );
        }

        if (strpos($key, '.')) {
            $segment = explode('.', $key);
            $key     = array_shift($segment);
            $cSeg    = count($segment);
            $object  = new stdClass;
            $parent  = null;

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

            $value = $object;
        }

        if (!$section) {
            $this->_config->$key = $value;
        } else {
            $this->_config->$section->$key = $value;
        }

    }


}
