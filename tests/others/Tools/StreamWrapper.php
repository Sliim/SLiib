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
 * @package    Tests
 * @subpackage Tools
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link       http://www.sliim-projects.eu
 */

namespace Tools;

/**
 * Tools\StreamWrapper
 * Used to manipulate php stdout and stderr stream in \SLiib\LogTest
 *
 * @package    Tests
 * @subpackage Tools
 */
class StreamWrapper
{

    /**
     * Global variable name
     * @var string
     */
    private $_name;


    /**
     * Open stream, set variable name
     *
     * @param string $path Path to open
     *
     * @return boolean
     */
    public function stream_open($path)
    {
        $url                   = parse_url($path);
        $this->_name           = $url['host'];
        $GLOBALS[$this->_name] = NULL;

        return TRUE;

    }


    /**
     * Write stream, appends data in global variable
     *
     * @param string $data Data to appends
     *
     * @return int
     */
    public function stream_write($data)
    {
        $GLOBALS[$this->_name] .= $data;
        return strlen($data);

    }


    /**
     * Read stream, used to set global variable to NULL.
     * It's dirty but no stream match to do this action, ftruncate report an E_WARNING :/
     *
     * @return void
     */
    public function stream_read()
    {
        $GLOBALS[$this->_name] = NULL;

    }


    /**
     * EOF stream, get global variable length
     *
     * @return int
     */
    public function stream_eof()
    {
        return strlen($GLOBALS[$this->_name]);

    }


}
