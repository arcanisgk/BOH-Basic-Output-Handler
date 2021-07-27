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

/*
 * Fast implementation of default data output...
 * @param $args
 */
function boh(...$args)
{
    /*
    $output = new Output();
    foreach ($args as $arg) {
        $output->output($arg);
    }
    */
    foreach ($args as $arg) {
        echo '<pre>';
        echo var_dump($arg);
        echo '<pre>';
    }
}

/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 */
class Output
{
    /**
     * auto-instantiate tool kit of Validation class.
     * @var ?Output
     */
    private static ?Output $instance = null;
    /**
     * Store value if Running from Terminal/Command-Line Environment.
     * @var bool
     */
    public bool $ifTerminal = false;
    /**
     * instantiate tool kit of Designer class.
     * @var Designer
     */
    protected Designer $designer;
    /**
     * instantiate tool kit of Reflector class.
     * @var Reflector
     */
    private Reflector $reflector;
    /**
     * instantiate tool kit of Commons class.
     * @var Commons
     */
    private Commons $commons;

    /**
     * Constructor of the Class Output
     */
    public function __construct()
    {
        $this->reflector  = new Reflector();
        $this->designer   = new Designer();
        $this->commons    = new Commons();
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
     *
     * @param $data
     */
    public function output($data)
    {
        !$this->validateEnvironment() ?: die('You are trying to use the output from a terminal we recommend using outputTerminal method.');
        $data   = $this->reflector->initReflectVariable($data);
        $indent = $this->designer->getIndent($data);
        $layout = $this->designer->getLayout($data, $indent);

        echo '<pre>';
        echo var_dump($indent, $layout);
        echo '</pre>';


        //Hydrate Data

        //$indents = $this->designer->getIndent($data);
        //$string  = $this->validator->getVariableToText($data, $indents);

    }

    public function validateEnvironment(): bool
    {
        return $this->ifTerminal;
    }

    /**
     *
     * @param $data
     */
    public function outputJson($data)
    {
        //under Development
    }

    /**
     *
     * @param $data
     */
    public function outputTerminal($data)
    {
        $this->validateEnvironment() ?: die('You are trying to use the outputTerminal from a Website we recommend using output method.');
        //under Development
    }
}

