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
 * @package    SLiib
 * @subpackage UnitTests
 * @author     Sliim <sliim@mailoo.org>
 * @license    GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @version    Release: 0.2
 * @link       http://www.sliim-projects.eu
 */
require_once 'PHPUnit/Framework.php';
require_once 'SLiib/SystemInfos.php';

/**
 * Test class for SLiib_SystemInfos.
 * Generated by PHPUnit on 2011-10-14 at 20:56:19.
 *
 * @package    SLiib
 * @subpackage UnitTests
 */
class SLiib_SystemInfosTest extends PHPUnit_Framework_TestCase
{


    /**
     * Exécute une commande
     *
     * @param string $cmd Commande à exécuter
     *
     * @return string Retour de la commande
     */
    private function _exec($cmd)
    {
        try {
            $res = SLiib_SystemInfos::$cmd();
        } catch (SLiib_SystemInfos_BadCommandException $e) {
            $this->markTestSkipped('Command unknown !');
        } catch (SLiib_SystemInfos_CommandFailedException $e) {
            $this->markTestSkipped('Command failed !');
        }

        return $res;

    }


    /**
     * Appel commande Apache2
     *
     * @return void
     */
    public function testCmdApache2()
    {
        $res = $this->_exec('CMD_APACHE2_VERSION');
        $this->assertType('string', $res);

        $res = $this->_exec('CMD_APACHE2_COMPILED_MODULES');
        $this->assertType('string', $res);

    }


    /**
     * Appel commande PHP
     *
     * @return void
     */
    public function testCmdPHP()
    {
        $res = $this->_exec('CMD_PHP_VERSION');
        $this->assertType('string', $res);

        $res = $this->_exec('CMD_PHP_MODULES');
        $this->assertType('string', $res);

    }


    /**
     * Appel commande Uname
     *
     * @return void
     */
    public function testCmdUname()
    {
        $res = $this->_exec('CMD_UNAME_KERNEL_RELEASE');
        $this->assertType('string', $res);

        $res = $this->_exec('CMD_UNAME_OS_INFOS');
        $this->assertType('string', $res);

    }


    /**
     * Appel commande LSBRelease
     *
     * @return void
     */
    public function testCmdLsbRelease()
    {
        $res = $this->_exec('CMD_LSB_RELEASE_CODENAME');
        $this->assertType('string', $res);

        $res = $this->_exec('CMD_LSB_RELEASE_RELEASE');
        $this->assertType('string', $res);

    }


}