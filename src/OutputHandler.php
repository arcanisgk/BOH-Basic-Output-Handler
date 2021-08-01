<?php

/**
 * src - Data toPlain manager in PHP development environments.
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

namespace IcarosNet\BOH;

/**
 * Validation of php version.
 * strictly equal to or greater than 7.4
 * a minor version will kill any script.
 */

if (!version_compare(PHP_VERSION, '7.4', '>=')) {
    die('IcarosNet\BOHBasicOutputHandler requires PHP ver. 7.4 or higher');
}

/*
 * Fast implementation of default data toPlain...
 * @param $args
 */
function boh(...$args)
{
    /*
    $toPlain = new OutputHandler();
    foreach ($args as $arg) {
        $toPlain->toPlain($arg);
    }
    */
    foreach ($args as $arg) {
        echo '<pre>';
        echo var_dump($arg);
        echo '<pre>';
    }
}

/**
 * src - Data toPlain manager in PHP development environments.
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 */
class OutputHandler
{

    /**
     * auto-instantiate tool kit of Validation class.
     * @var ?OutputHandler
     */
    private static ?OutputHandler $instance = null;

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
     * instantiate tool kit of ThemeController class.
     * @var ThemeController
     */
    private ThemeController $theme_controller;

    /**
     * Constructor of the Class OutputHandler
     */
    public function __construct(string $theme = 'default')
    {
        $this->reflector        = new Reflector();
        $this->designer         = new Designer();
        $this->commons          = new Commons();
        $this->theme_controller = new ThemeController();
        $this->theme_controller->setTheme($theme);
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

    public static function getInstance(): OutputHandler
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
    public function toPlain($data)
    {
        !$this->validateEnvironment() ?: die('You are trying to use the toPlain from a terminal we recommend using toTerminal method.');

        $data   = $this->reflector->initReflectVariable($data);
        $indent = $this->designer->getIndent($data);

        //echo '<pre>';
        //echo var_dump($indent);
        //echo '</pre>';

        echo '<div style="font-family: monospace;background-color: black;color: white">' . $this->designer->getLayout($data, $indent) . '</div>';

        //echo $layout = nl2br($layout);
        //echo '<pre>';
        //echo var_dump($layout);
        //echo '</pre>';


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
     * @param $otros
     */
    public function loadBOHDesign($data, $otros)
    {
        //under Development
    }

    /**
     *
     * @param $data
     */
    public function toWeb($data)
    {
        //under Development
    }

    /**
     *
     * @param $data
     */
    public function toJson($data)
    {
        //under Development
    }

    /**
     *
     * @param $data
     */
    public function toTerminal($data)
    {
        $this->validateEnvironment() ?: die('You are trying to use the toTerminal from a Website we recommend using toPlain method.');
        //under Development
    }

    public function setOptions(array $array)
    {

    }
}

