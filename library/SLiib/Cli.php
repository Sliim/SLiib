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
 * @package  SLiib\Cli
 * @author   Sliim <sliim@mailoo.org>
 * @license  GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.sliim-projects.eu
 */

namespace SLiib;

/**
 * \SLiib\Cli
 *
 * @package SLiib\Cli
 */
abstract class Cli
{
    const NO_DESCRIPTION_LABEL = 'No description';
    const AUTHOR_UNKNOWN_LABEL = 'Inconnu';
    const AUTHOR_LABEL         = 'Auteur';
    const VERSION_LABEL        = 'Version';
    const SHOW_HELP_LABEL      = 'Affiche l\'aide.';
    const SHOW_VERSION_LABEL   = 'Affiche la version du script.';

    /**
     * Script version
     * @var float
     */
    protected $_version = 0;

    /**
     * Script description
     * @var string
     */
    protected $_desc = self::NO_DESCRIPTION_LABEL;

    /**
     * Script author
     * @var string
     */
    protected $_author = self::AUTHOR_UNKNOWN_LABEL;

    /**
     * Default options of script
     * @var array
     */
    protected $_defaultOpt = array(
                              'V' => array(
                                      'desc' => self::SHOW_VERSION_LABEL,
                                      'func' => 'version',
                                     ),
                              'h' => array(
                                      'desc' => self::SHOW_HELP_LABEL,
                                      'func' => 'help',
                                     )
                             );

    /**
     * Available options
     * @var array
     */
    protected $_options = null;

    /**
     * GetOpt parameter
     * @var string
     */
    protected $_params;

    /**
     * Constructor
     *
     * @param array $options Available options
     *
     * @return void
     */
    public function __construct (array $options = null)
    {
        if (!is_null($options)) {
            $this->_options = array_merge($this->_defaultOpt, $options);

            $params = '';
            foreach ($this->_options as $option => $desc) {
                if (!array_key_exists('desc', $desc)) {
                    $this->_options[$option]['desc'] = self::NO_DESCRIPTION;
                }

                $params .= $option;

                $this->_options[str_replace(':', '', $option, $count)] =
                  $this->_options[$option];
                if ($count > 0) {
                    unset($this->_options[$option]);
                }
            }

            $this->_params = getopt($params);

            foreach ($this->_params as $param => $value) {
                if (array_key_exists('func', $this->_options[$param])) {
                    $func = $this->_options[$param]['func'];
                    if (function_exists($this->$func($value))) {
                        $this->$func($value);
                    }
                }
            }
        }
    }

    /**
     * Show help
     *
     * @return void
     */
    protected function help ()
    {
        echo $this->_desc . PHP_EOL . PHP_EOL;
        if (!is_null($this->_options)) {
            foreach ($this->_options as $opt => $optDesc) {
                echo "\t-" . $opt . "\t" . $optDesc['desc'] . PHP_EOL;
            }
        }

        echo PHP_EOL;
        echo self::AUTHOR_LABEL . ' : ' . $this->_author . PHP_EOL;
        echo self::VERSION_LABEL . ' : ' . $this->_version . PHP_EOL;
        exit();
    }

    /**
     * Show version
     *
     * @return void
     */
    protected function version ()
    {
        echo $this->_version . PHP_EOL;
        exit();
    }
}

