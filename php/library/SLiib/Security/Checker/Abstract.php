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
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Checker_Abstract
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */

/**
 * SLiib_Security_Checker_Abstract
 *
 * @package    SLiib_Security
 * @subpackage SLiib_Security_Checker_Abstract
 */
abstract class SLiib_Security_Checker_Abstract
{
    const LOCATION_CONTROLLER = 'Controller';
    const LOCATION_ACTION     = 'Action';
    const LOCATION_PARAMS     = 'Params';

    protected $_name;
    protected $_patterns = array();

    public final function run()
    {
        foreach ($this->_patterns as $pattern => $datas) {
            //TODO faire des tests sur les types
            /*TODO Distinguer deux type d'exceptions :
                - Les exception de sécurité : correspond à une faille de sécurité levé dans la requête
             *  - Les exception applicative : correspondent à une mauvaise utilisation de SLiib_Security
             */
            foreach ($datas['locations'] as $location) {
                $attempt = true;
                switch ($location) {
                    case self::LOCATION_CONTROLLER:
                        $attempt = $this->_checkController($pattern);
                        break;
                    case self::LOCATION_ACTION:
                        $attempt = $this->_checkAction($pattern);
                        break;
                    case self::LOCATION_PARAMS:
                        $attempt = $this->_checkParams($pattern);
                        break;
                    default:
                        throw new SLiib_Security_Exception_CheckerError;
                        break;
                }

                if (!$attempt) {
                    throw new SLiib_Security_Exception_HackingAttempt(
                        $this->_name, $datas['type'], $location
                    );
                }


            }
        }
    }



    private final function _checkController($pattern)
    {
        $controller = SLiib_HTTP_Request::getController();

        if (preg_match('/' . $pattern . '/', $controller)) {
            return false;
        }

        return true;

    }

    private final function _checkAction($pattern)
    {
        $action = SLiib_HTTP_Request::getAction();

        if (preg_match('/' . $pattern . '/', $action)) {
            return false;
        }

        return true;
    }

    private final function _checkParams($pattern)
    {
        $params = SLiib_HTTP_Request::getParameters();

        foreach ($params as $key => $value) {
            if (preg_match('/' . $pattern . '/', $value)) {
               return false;
            }

            if (preg_match('/' . $pattern . '/', $value)) {
               return false;
            }
        }

        return true;
    }

}