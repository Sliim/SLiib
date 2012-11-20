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
 * @package  SLiib\Listing
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;


/**
 * \SLiib\Listing
 *
 * @package SLiib\Listing
 */
class Listing
{

    /**
     * Directory path
     * @var string
     */
    private $path = '';

    /**
     * Array of directory elements
     * @var array
     */
    private $list = array();

    /**
     * Number of elements in directory
     * @var int
     */
    private $contentNb;

    /**
     * Directory name
     * @var string
     */
    private $name;

    /**
     * Constructor, get directory path
     *
     * @param string $dirPath  Directory path
     * @param string $listName List name
     * @param array  $exclude  Elements to exclude
     *
     * @throws Listing\Exception
     *
     * @return void
     */
    public function __construct($dirPath, $listName, array $exclude)
    {
        if (!is_dir($dirPath)) {
            throw new Listing\Exception('Directory `' . $dirPath . '` not found!');
        }

        $this->path      = $dirPath;
        $this->contentNb = 0;
        $this->name      = $listName;

        $this->setList($exclude);
    }

    /**
     * Listing getter
     *
     * @return array Liste des éléments du dossier
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Sort list
     *
     * @return \SLiib\Listing
     */
    public function sort()
    {
        $list = $this->getList();
        natcasesort($list);

        $this->list = array_merge($list);
        return $this;
    }

    /**
     * Usort list
     *
     * @return \SLiib\Listing
     */
    public function usort()
    {
        $list = $this->getList();
        natcasesort($list);

        $this->list = array_reverse(array_merge($list));
        return $this;
    }

     /**
     * Get directory elements and set the list
     *
     * @param array $exclude Elements to exclude
     *
     * @return void
     */
    private function setList(array $exclude)
    {
        $dir = opendir($this->path);

        while ($element = readdir($dir)) {
            foreach ($exclude as $e) {
                if ($element != $e && !preg_match('/~$/i', $element)) {
                    $this->contentNb++;
                    $this->list[] = $element;
                }
            }
        }

        closedir($dir);
    }
}
