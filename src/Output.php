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
 * On-screen exit help for Library developers..
 * @param $args
 */
function dd(...$args)
{
    echo '<pre>';
    echo var_dump($args);
    echo '</pre>';
}

/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 */
class Output
{
    /**
     * instantiate tool kit of Reflector class.
     * @var Reflector
     */
    private Reflector $reflector;
    /**
     * instantiate tool kit of Designer class.
     * @var Designer
     */
    protected Designer $designer;
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
     * Store value if Running from Terminal/Command-Line Environment.
     * @var bool
     */

    public function __construct()
    {
        $this->reflector  = new Reflector();
        $this->designer   = new Designer();
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
        //Dehydrate Data With Reflector.

        $result = $this->reflector->initReflectVariable($data);
        echo '<pre>';
        echo var_dump($result);
        echo '</pre>';

        //Hydrate Data

        //$indents = $this->designer->getIndent($data);
        //$string  = $this->validator->getVariableToText($data, $indents);

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

    public function validateEnvironment(): bool
    {
        return $this->ifTerminal;
    }
}

