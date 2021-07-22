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

/**
 * Validation of php version.
 * strictly equal to or greater than 7.4
 * a minor version will kill any script.
 */

if (!version_compare(PHP_VERSION, '7.4', '>=')) {
    die('IcarosNet\BOHBasicOutputHandler requires PHP ver. 7.4 or higher');
}

/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 */
class Output extends Designer
{
    private static $instance = null;
    /**
     * Store value if Running from Terminal/Command-Line Environment.
     * @var bool
     */
    public bool $ifTerminal = false;
    public array $setOptions = [];

    public function __construct()
    {
        parent::__construct();
        $this->ifTerminal = $this->checkTerminal();
    }

    /**
     * Determinate if Running from Terminal/Command-Line Environment.
     * @return bool
     */
    public function checkTerminal(): bool
    {
        return defined('STDIN')
            || php_sapi_name() === "cli"
            || (stristr(PHP_SAPI, 'cgi') && getenv('TERM'))
            || (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0);
    }

    public static function getInstance(): Output
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param  array|string[]  $option
     */
    public function setOptions(array $option = self::DEFAULTOPTIONS)
    {
        //$this->
        //$color = isset(self::THEMES[$theme]) ? self::THEMES[$theme] : self::THEMES['default'];
    }

    /*
    public function loadBOHDesign(array $option = self::DEFAULTOPTIONS)
    {
        $this->


        $color = isset(self::THEMES[$theme]) ? self::THEMES[$theme] : self::THEMES['default'];
    }
    */


    /**
     *
     * @param $data
     */
    public function output($data)
    {
        !$this->validateEnvironment() ?: die('You are trying to use the output from a terminal we recommend using outputTerminal method.');
        $indents = $this->getIndent($data);
        $string  = $this->getVariableToText($data, $indents);

        echo '<pre>';
        echo $string;
        echo var_dump($data);
        echo '</pre>';
    }

    /**
     *
     * @param $data
     */
    public function outputJson($data)
    {
        //echo '<pre>';
        //echo var_dump($data);
        //echo '</pre>';
    }

    /**
     *
     * @param $data
     */
    public function outputTerminal($data)
    {
        $this->validateEnvironment() ?: die('You are trying to use the outputTerminal from a Website we recommend using output method.');
        //echo '<pre>';
        //echo var_dump($data);
        //echo '</pre>';
    }

    public function validateEnvironment()
    {
        return $this->ifTerminal;
    }


}