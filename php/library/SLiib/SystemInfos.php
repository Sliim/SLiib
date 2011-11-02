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
 * @package  SLiib_SystemInfos
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version  Release: 0.2
 * @link     http://www.sliim-projects.eu
 */

/**
 * SLiib_SystemInfos
 *
 * @package SLiib_SystemInfos
 */
class SLiib_SystemInfos implements
SLiib_SystemInfos_Interfaces_IUname,
SLiib_SystemInfos_Interfaces_IPhp,
SLiib_SystemInfos_Interfaces_IApache2,
SLiib_SystemInfos_Interfaces_ILsbRelease
{


    /**
     * Cette méthode magique est appelée lorqu'une méthode est appelée
     * statiquement. Celle ci se charge de vérifier si le nom de la méthode
     * correspond à une constante, si la constante existe, la valeur de la
     * constante est éxécuté en tant que commande.
     *
     * @param string $name      Nom de la méthode appelée
     * @param array  $arguments Arguments passés à la méthode. Un argument
     *                          possible : 'serialize' qui permet de récupérer
     *                          le résultat de la commande sérialisé.
     *
     * @throws SLiib_SystemInfos_Exception_BadMethodCall
     *
     * @return string résultat de la commande
     */
    public static function __callStatic($name, $arguments)
    {
        if (!defined('self::' . $name)) {
            throw new SLiib_SystemInfos_Exception_BadMethodCall('Command not found!');
        }

        $result = self::_execute(constant('SELF::' . $name));

        if (in_array('serialize', $arguments)) {
            return serialize($result);
        } else {
            return implode($result, '');
        }

    }


    /**
     * Exécute une commande avec proc_open
     *
     * @param string $cmd La commande a exécuter
     *
     * @throws SLiib_SystemInfos_Exception_CommandFailed
     *
     * @return array Tableau contenant le résultat de la commande.
     */
    private static function _execute($cmd)
    {
        $resultValue    = array();
        $descriptorspec = array(
                           0 => array(
                                 'pipe',
                                 'r',
                                ),
                           1 => array(
                                 'pipe',
                                 'w',
                                ),
                           2 => array(
                                 'pipe',
                                 'w',
                                ),
                          );

        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            fclose($pipes[0]);

            while (!feof($pipes[1])) {
                $resultValue[] = fgets($pipes[1], 1024);
            }

            fclose($pipes[1]);
            $returnValue = proc_close($process);

            if ($returnValue != 0) {
                throw new SLiib_SystemInfos_Exception_CommandFailed(
                    'Command `' . $cmd . '` failed!'
                );
            }

            return $resultValue;
        }

        return array();

    }


}
