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
 * @package  SLiib\Listing
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * Namespace
 */
namespace SLiib;

/**
 * Uses
 */
use SLiib\Listing\Exception;

/**
 * SLiib\Listing
 *
 * @package SLiib\Listing
 */
class Listing
{

    /**
     * Chemin du dossier à lister
     * @var string
     */
    private $_path = '';

    /**
     * Tableau comportant les éléments du dossier listé
     * @var array
     */
    private $_list = array();

    /**
     * Nombre d'élément dans le dossier listé
     * @var string
     */
    private $_contentNb;

    /**
     * Nom du dossier
     * @var string
     */
    private $_name;


    /**
     * Constructeur, récupère le chemin du dossier qui sera à lister
     *
     * @param string $dirPath    Chemin du dossier à lister
     * @param string $listName   Nom de la liste
     * @param array  $exceptions Liste des exceptions à ne pas lister
     *
     * @throws Exception
     *
     * @return void
     */
    public function __construct($dirPath, $listName, $exceptions)
    {
        if (!is_dir($dirPath)) {
            throw new Exception('Directory `' . $dirPath . '` not found!');
        }

        $this->_path      = $dirPath;
        $this->_contentNb = 0;
        $this->_name      = $listName;

        $this->_list($exceptions);

    }


    /**
     * Retourne la liste du contenu du dossier listé
     *
     * @return array Liste des éléments du dossier
     */
    public function getList()
    {
        return $this->_list;

    }


    /**
     * Rangement du tableau par ordre alphabetic
     *
     * @return SLiib\Listing
     */
    public function sort()
    {
        $list = $this->getList();
        natcasesort($list);

        $this->_list = array_merge($list);
        return $this;

    }


    /**
     * Rangement du tableau par ordre alphabetic inversé
     *
     * @return SLiib\Listing
     */
    public function usort()
    {
        $list = $this->getList();
        natcasesort($list);

        $this->_list = array_reverse(array_merge($list));
        return $this;

    }


     /**
     * Methode de remplissage du tableau. Créé la liste des dossiers et
     * fichiers présents. 1 paramètre : tableau contenant une liste de
     * fichier / dossier à ne pas lister.
     *
     * @param array $except Les exceptions à ne pas lister
     *
     * @return void
     */
    private function _list($except)
    {
        $rep = opendir($this->_path);

        while ($dossier = readdir($rep)) {
            $ok = 1;

            foreach ($except as $e) {
                if ($dossier == $e || preg_match('/~$/i', $dossier)) {
                    $ok = -1;
                }
            }

            if ($ok == 1) {
                $this->_contentNb++;
                $this->_list[] = $dossier;
            }
        }

        closedir($rep);

    }


}
