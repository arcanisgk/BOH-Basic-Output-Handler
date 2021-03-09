<?php
/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
 * PHP Version 7.4.
 *
 * @see https://github.com/arcanisgk/BOH-Basic-Output-Handler
 *
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 * @copyright 2020 - 2021 Walter Nuñez.
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

namespace IcarosNet\BOHBasicOutputHandler;

use Exception;

/**
 * Validation of php version.
 * strictly equal to or greater than 7.4
 * a minor version will kill any script.
 *
 */
if (!version_compare(PHP_VERSION, '7.4', '>=')) {
    die('IcarosNet\BOHBasicOutputHandler requires PHP ver. 7.4 or higher');
}

/**
 * Validation of the environment of use.
 * support for web and cli environments
 *
 */
if (!defined('ENVIRONMENT_OUTPUT_HANDLER')) {
    define('ENVIRONMENT_OUTPUT_HANDLER', Validation::IsCommandLineInterface() ? 'cli' : 'web');
}

/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
 *
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 */
class OutputHandler extends OutputDesigner
{

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Destructor.
     *
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Check and Execute the request to show the formatted data.
     *
     * @param  mixed  $var
     * @param  null|string  $env
     * @param  bool  $retrieve
     *
     * @return void|string
     * @throws Exception
     */
    public function output($var, $env = null, $retrieve = false)
    {
        $env = $this->validateEnvironment($env);
        return $env == 'web' ? $this->outputWeb($var, $retrieve) : $this->outputCli($var, $retrieve);
    }

    /**
     * Check and Execute the request to show the formatted data for web environment.
     *
     * @param  mixed  $var
     * @param  bool  $retrieve
     *
     * @return void|string
     */
    public function outputWeb($var, bool $retrieve): string
    {

        $string  = '';
        $indents = $this->getIndent($var);

        $string = $this->analyzeVariable($var, $indents);

        $string = $this->highlightCode($string);
        //$string = $this->applyCss($string);

        $this->resetHighlight();

        if ($retrieve) {
            return $string;
        } else {
            $this->outView($string);
            return '';
        }
    }

    /**
     * Check and Execute the request to show the formatted data for web environment.
     *
     * @param  mixed  $var
     * @param  bool  $retrieve
     *
     * @return void|string
     */
    public function outputCli($var, bool $retrieve): string
    {
        $string = '';

        if ($retrieve) {
            return $string;
        } else {
            $this->outView($string);
            return '';
        }
    }

    /**
     * This should send the text on screen.
     *
     * @param  string  $string
     */
    private function outView(string $string): void
    {
        echo $string;
    }
}