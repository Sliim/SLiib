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
 * @package  SLiib\SolR
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\SolR
 *
 * @package SLiib\SolR
 */
class SolR
{

    const UPDATE_DIRECTORY = '/solr/update';
    const SELECT_DIRECTORY = '/solr/select';

    /**
     * SolR host
     * @var string
     */
    protected $_host;

    /**
     * SolR port
     * @var int
     */
    protected $_port;

    /**
     * Special characters specific to solr
     * @var array
     */
    protected $_specialChars = array(
                                '+', '-',
                                '&', '|',
                                '!', '(',
                                ')', '{',
                                '}', '[',
                                ']', '^',
                                '"', '~',
                                '*', '?',
                                ':',
                               );


    /**
     * Constructor
     *
     * @param string  $host SolR host
     * @param int     $port SolR port
     * @param boolean $ping Check if accessible.
     *
     * @throws \SLiib\SolR\Exception
     *
     * @return void
     */
    public function __construct($host, $port, $ping=TRUE)
    {
        $this->_host = $host;
        $this->_port = $port;

        if (!$ping) {
            return;
        }

        if (!$this->ping()) {
            throw new SolR\Exception('SolR is down, please verify it\'s running..');
        }

    }


    /**
     * Index an XML string
     *
     * @param string $xmlString XML string to index
     *
     * @return boolean
     */
    public function update($xmlString)
    {
        $fp = @fsockopen($this->_host, $this->_port, $errno, $errstr, 30);
        if (!$fp) {
            return FALSE;
        }

        $out  = 'POST ' . self::UPDATE_DIRECTORY . " HTTP/1.1\r\n";
        $out .= 'Host: ' . $this->_host . ':' . $this->_port . "\r\n";
        $out .= "Accept: */*\r\n";
        $out .= "Content-Type: text/xml;charset=utf-8\r\n";
        $out .= 'Content-Length: ' . strlen($xmlString) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $out .= $xmlString;

        if (fwrite($fp, $out)) {
            $response = '';
            while (!feof($fp)) {
                $response .= fgets($fp, 128);
            }

            if (preg_match('/<lst(.*)?>(.*)?<\/lst>/', $response, $matches)) {
                fclose($fp);
                return TRUE;
            }
        }

        fclose($fp);
        return FALSE;

    }


    /**
     * Index commiter
     *
     * @return void
     */
    public function commit()
    {
        $query = '<commit />';
        $this->update($query);

    }


    /**
     * Delete all elements in index
     *
     * @return void
     */
    public function deleteAll()
    {
        $query = '<delete><query>*:*</query></delete>';
        $this->update($query);

        $this->commit();

    }


    /**
     * Search in solr index
     *
     * @param string $query Query to send
     *
     * @return string
     */
    public function get($query)
    {
        $query       = '?q=' . $query;
        $xmlResponse = '';

        $fp = @fsockopen($this->_host, $this->_port, $errno, $errstr, 30);

        if (!$fp) {
            return FALSE;
        }

        $out  = 'GET ' . self::SELECT_DIRECTORY . '/' . $query . " HTTP/1.1\r\n";
        $out .= 'Host: ' . $this->_host . ':' . $this->_port . "\r\n";
        $out .= "Accept: */*\r\n";
        $out .= "Connection: Close\r\n\r\n";

        if (fwrite($fp, $out)) {
            while (!feof($fp)) {
                $xmlResponse .= fgets($fp, 128);
            }

            fclose($fp);

            $xmlResponse = str_replace(array("\r", "\n"), '', $xmlResponse);
            if (preg_match(
                '/<response(.*)?>(.*)?<\/response>/', $xmlResponse, $matches
            )) {
                return $matches[0];
            }
        }

        return FALSE;

    }


    /**
     * Get element number in solr index
     *
     * @return int Nombre d'élément.
     */
    public function getTotalIndexed()
    {
        $xml = $this->get('*%3A*');
        if (!$xml) {
            return FALSE;
        }

        $obj = simplexml_load_string($xml);
        return (int) $obj->result['numFound'];

    }


    /**
     * Check if solr is accessible
     *
     * @return boolean
     */
    public function ping()
    {
        $fp = @fsockopen($this->_host, $this->_port);

        if (!$fp) {
            return FALSE;
        }

        fclose($fp);
        return TRUE;

    }


    /**
     * Escape special characters
     *
     * @param string $string Chaine à traiter.
     *
     * @return string Chaine Traitée.
     */
    public function escapeSpecialChar($string)
    {
        foreach ($this->_specialChars as $char) {
            $string = str_replace($char, '\\' . $char, $string);
        }

        return $string;

    }


}