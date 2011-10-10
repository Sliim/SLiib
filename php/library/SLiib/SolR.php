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
 * @package  Library
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.1.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_SolR
 * 
 * @package SolR
 */
class SLiib_SolR
{

  /**
   * Host de l'instance SolR
   *
   * @var string $_host
   */
  protected $_host;

  /**
   * Port écouté par l'instance SolR
   *
   * @var int $_port
   */
  protected $_port;

  /**
   * Tableau des caractères spéciaux spécifiques à Solr
   *
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
   * Répertoire SolR pour les update (insertion, commit, delete ..)
   *
   * @const string UPDATE_DIRECTORY
   */
  const UPDATE_DIRECTORY = '/solr/update';

  /**
   * Répertoire de sélection de document avec des requêtes spécifiques
   *
   * @const string SELECT_DIRECTORY
   */
  const SELECT_DIRECTORY = '/solr/select';


  /**
   * Constructeur, initialise les attributs privés.
   *
   * @param string $host Host de l'instance SolR
   * @param int    $port Port écouté par l'instance SolR
   *
   * @throws SLiib_SolR_Exception
   * 
   * @return void
   */
  public function __construct($host, $port)
  {
    $this->_host = $host;
    $this->_port = $port;

    if (!$this->ping())
      throw new SLiib_SolR_Exception(
          'SolR is down, please verify it\'s running..'
      );

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
    if (!$fp)
      return false;

    $out  = 'POST ' . self::UPDATE_DIRECTORY . " HTTP/1.1\r\n";
    $out .= 'Host: ' . $this->_host . ':' . $this->_port . "\r\n";
    $out .= "Accept: */*\r\n";
    $out .= "Content-Type: text/xml;charset=utf-8\r\n";
    $out .= 'Content-Length: ' . strlen($xmlString) . "\r\n";
    $out .= "Connection: Close\r\n\r\n";
    $out .= $xmlString;

    if (fwrite($fp, $out)) {
      $response = '';
      while (!feof($fp))
        $response .= fgets($fp, 128);

      if (preg_match('/<lst(.*)?>(.*)?<\/lst>/', $response, $matches)) {
        fclose($fp);
        return true;
      }
    }

    fclose($fp);
    return false;

  }


  /**
   * Commit de l'index SolR
   *
   * @return void
   */
  public function commit()
  {
    $query = '<commit />';
    echo 'Committing.. ';
    $this->update($query);
    echo 'done.' . PHP_EOL;

  }


  /**
   * Suppression de la totalité de l'index SolR.
   *
   * @return void
   */
  public function deleteAll()
  {
    $query = '<delete><query>*:*</query></delete>';
    echo 'Deleting all document.. ';
    $this->update($query);
    echo 'done.' . PHP_EOL;

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

    if (!$fp)
      return false;

    $out  = 'GET ' . self::SELECT_DIRECTORY . '/' . $query . " HTTP/1.1\r\n";
    $out .= 'Host: ' . $this->_host . ':' . $this->_port . "\r\n";
    $out .= "Accept: */*\r\n";
    $out .= "Connection: Close\r\n\r\n";

    if (fwrite($fp, $out)) {
      while (!feof($fp))
        $xmlResponse .= fgets($fp, 128);
      fclose($fp);

      $xmlResponse = str_replace(array("\r", "\n"), '', $xmlResponse);
      if (preg_match(
          '/<response(.*)?>(.*)?<\/response>/', $xmlResponse, $matches
      )) {
        return $matches[0];
      }

      return false;
    }

  }


  /**
   * Récupère le nombre d'élément indexé dans solr.
   *
   * @return string Nombre d'élément.
   */
  public function getTotalIndexed()
  {
    $xml = $this->get('*%3A*');
    if (!$xml)
      return false;

    $obj = simplexml_load_string($xml);
    return $obj->result['numFound'];

  }


  /**
   * Effectue un ping vers SolR
   *
   * @return boolean
   */
  public function ping()
  {
    $fp = @fsockopen($this->_host, $this->_port, $errno, $errstr, 30);
    if (!$fp)
      return false;
    return true;

  }


  /**
   * Echappe les caractères spéciaux propre à SolR d'une chaine.
   *
   * @param string $string Chaine à traiter.
   *
   * @return $string Chaine Traitée.
   */
  public function escapeSpecialChar($string)
  {
    foreach ($this->_specialChars as $char)
      $string = str_replace($char, '\\' . $char, $string);

    return $string;

  }


}