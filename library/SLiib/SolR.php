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
 * @package  SLiib\SolR
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;
use SLiib\SolR;

/**
 * \SLiib\SolR
 *
 * @package SLiib\SolR
 */
class SolR
{

    /**
     * Répertoire SolR pour les update (insertion, commit, delete ..)
     * @const string UPDATE_DIRECTORY
     */
    const UPDATE_DIRECTORY = '/solr/update';

    /**
     * Répertoire de sélection de document avec des requêtes spécifiques
     * @const string SELECT_DIRECTORY
     */
    const SELECT_DIRECTORY = '/solr/select';

    /**
     * Host de l'instance SolR
     * @var string
     */
    protected $_host;

    /**
     * Port écouté par l'instance SolR
     * @var int
     */
    protected $_port;

    /**
     * Tableau des caractères spéciaux spécifiques à Solr
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
     * Constructeur, initialise les attributs privés.
     *
     * @param string            $host Host de l'instance SolR
     * @param int               $port Port écouté par l'instance SolR
     * @param boolean[optional] $ping Ping SolR
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
     * Index un document XML
     *
     * @param string $xmlString Chaine de caractère XML
     *
     * @return Boolean True si OK False si KO
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
     * Commit de l'index SolR
     *
     * @return void
     */
    public function commit()
    {
        $query = '<commit />';
        $this->update($query);

    }


    /**
     * Suppression de la totalité de l'index SolR.
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
     * Effectue une recherche dans l'index SolR.
     *
     * @param string $query Requête SolR à émettre (possibilité d'utilises les
     *                      paramètre hl, fl etc.. spécifique à SolR).
     *
     * @return string Résultat obtenu au format XML.
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
     * Récupère le nombre d'élément indexé dans solr.
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
     * Effectue un ping vers SolR
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
     * Echappe les caractères spéciaux propre à SolR d'une chaine.
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