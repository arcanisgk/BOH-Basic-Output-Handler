<?php

/**
 * BOH - Data Output Manager in PHP Development Environments.
 * PHP Version required 7.4.* or higher
 * This example shows how the BOH class and its function/methods are declared.
 *
 * @see https://github.com/IcarosNetSA/BOH-Basic-Output-Handler
 *
 * @author    Walter Nuñez (arcanisgk/original founder)
 * @email     icarosnet@gmail.com
 * @copyright 2020 - 2021 Walter Nuñez.
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful
 *            WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 *            or FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

namespace IcarosNetSA\BOH;

use ReflectionException;

/**
 * Validation of php version.
 * strictly equal to or greater than 7.4
 * a minor version will kill any script.
 */

if (!version_compare(PHP_VERSION, '7.4', '>=')) {
    die('IcarosNetSA\BOH requires PHP ver. 7.4 or higher');
}

/**
 * OutputHandler Class.
 */
class OutputHandler
{

    /**
     * Description: instantiate Class Static.
     * @var OutputHandler|null $instance
     */

    private static ?OutputHandler $instance = null;

    /**
     * Description: Library configuration variable.
     * + Determines debug mode on/off, show commons options and environment.
     * - 'env': supported list
     *      - 'true'
     *      - 'false' (default)
     * @var bool $debug
     */

    private static bool $debug = false;

    /**
     * Description: Library configuration variable.
     * + Determines which of the web environments should run in the view.
     * - 'env': supported list
     *      - 'plain'
     *      - 'web' (default)
     *      - 'json'
     *      - 'cli'
     * @var string
     */

    private static string $env = 'web';

    /**
     * Description: Library configuration variable.
     * + Preload all the html, javascript in a separate web module from the original, it has no effect in cli environment.
     * - 'build': supported list
     *      - 'default' (default)
     *      - 'full'
     *      - 'modal'
     * @var string
     */

    private static string $build = 'default';

    /**
     * Description: Library configuration variable.
     * + Used only for the web and json environment, it determines which HTML template will be loaded based on the chosen css framework.
     * - 'css': supported list
     *      - 'default' (default)
     *      - 'bs5'
     *      - 'bs4'
     *      - 'bulma'
     *      - 'foundation'
     *      - 'jquery-ui'
     *      - 'semantic-ui'
     *      - 'uikit'
     *      - 'materialize'
     *      - 'pure'
     *      - 'tailwind'
     * @var string
     */

    private static string $css = 'default';

    /**
     * Description: Library configuration variable.
     * + Establishes the color palette that is used in the view / display of the information.
     * - 'theme':
     *      - 'default' (default)
     *      - 'monokai'
     *      - 'x-space'
     *      - 'mauro-dark'
     *      - 'natural-flow'
     *      - 'vs-code'
     *      - 'red-redemption'
     *      - 'gray-scale'
     * @var string
     */

    private static string $theme = 'default';

    /**
     * Description: Library configuration variable.
     * + set if we want to use indentation between name, values and comments.
     * - 'indent'
     *      - true (default)
     *      - false
     * @var bool
     */

    private static bool $indent = true;

    /**
     * Description: Library configuration variable.
     * + set if we want to return data instead of exposing it.
     * - 'return'
     *      - true
     *      - false (default)
     * @var bool
     */

    private static bool $return = false;

    /**
     * Description: instantiate tool kit of Commons class.
     * @var Commons
     */

    private Commons $commons;

    /**
     * Description: Store value if Running from Terminal/Command-Line Environment.
     * @var bool
     */

    private bool $ifTerminal;

    /**
     * Description: instantiate tool kit of Designer class.
     * @var Designer
     */

    private Designer $designer;

    /**
     * Description: instantiate tool kit of Analyzer class.
     * @var Analyzer
     */

    private Analyzer $analyzer;

    /**
     * Description: instantiate tool kit of ThemeController class.
     * @var ThemeController
     */

    private ThemeController $theme_controller;

    /**
     * Description: Constructor of the Class OutputHandler
     */

    public function __construct()
    {
        $this->ifTerminal       = $this->checkTerminal();
        $this->commons          = new Commons();
        $this->designer         = new Designer();
        $this->analyzer         = new Analyzer();
        $this->theme_controller = new ThemeController();
    }

    /**
     * Description: Determinate if Running from Terminal/Command-Line Environment.
     * @return bool
     */

    private function checkTerminal(): bool
    {
        return defined('STDIN')
            || php_sapi_name() === "cli"
            || (stristr(PHP_SAPI, 'cgi') && getenv('TERM'))
            || (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0);
    }

    /**
     * Description: Auto-Instance Helper.
     */

    public static function getInstance(): OutputHandler
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Description: Evaluates past configuration options.
     * @param  array  $array
     */

    public function setOptions(array $array): void
    {
        if ($this->commons->isEmpty($array)) {
            echo 'you are trying to pass empty array Options to setOptions() method and is not supported.';
            die;
        } else {
            foreach ($array as $key => $value) {
                if (isset(self::${$key})) {
                    self::${$key} = $value;
                } else {
                    echo 'the property: ' . $key . ' not exist in BOH.' . PHP_EOL;
                }
            }
        }
    }

    /**
     * Description: Main Method to expose the variable
     * @param $data
     * @return string|void
     */

    public function receiverData($data)
    {
        if (self::$debug === true) {
            dump([
                'theme'       => self::$theme,
                'environment' => self::$env,
                'css'         => self::$css,
                'build'       => self::$build,
                'indent'      => self::$indent,
                'return'      => self::$return,
            ]);
        }
        try {
            $this->theme_controller->setTheme(self::$theme, self::$env);
            switch (self::$env) {
                case 'cli':
                    $this->ifTerminal ?: die('You are trying to use the toTerminal from a Web we recommend using toTerminal method.');
                    //$this->toTerminal($data);
                    break;
                case 'web':
                    !$this->ifTerminal ?: die('You are trying to use the toWeb from a CLI we recommend using toTerminal method.');
                    $this->toWeb($data);
                    break;
                case 'json':
                    !$this->ifTerminal ?: die('You are trying to use the toJson from a CLI we recommend using toTerminal method.');
                    //$this->toJson($data);
                    break;
                default:
                    return $this->toPlain($data);
            }
        } catch (ReflectionException $e) {
            die('invalid argument');
        }
    }

    /**
     * Description: Method to expose the data of a variable in Html format ready to insert and show in html body
     * @param $data
     * @throws ReflectionException
     */

    public function toWeb($data)
    {
        $result = $this->prepareOutput($data);
        $result = $this->designer->stringHighlight($result);
        $result = $this->designer->wrapElement($result, $this->theme_controller->getBackGround(self::$theme));
        if (self::$build == 'full') {
            $result = $this->designer->addFull($result);
        } elseif (self::$build == 'modal') {
            $result = $this->designer->addModal($result);
        }
        return $this->output($result);
    }

    /**
     * @param $data
     * @return string
     * @throws ReflectionException
     */

    private function prepareOutput($data): string
    {
        $deep                  = ((int) ceil($this->commons->calculateDeepArray($data) + 1)) * 4;
        $description           = $this->analyzer->getVariableDescription($data);
        $indent                = $this->designer->getIndent($description, self::$indent, $deep);
        $description_string    = $this->analyzer->getAnalysisDescription(
            $indent,
            $description
        );
        $this->designer->theme = self::$theme;
        return $this->designer->addWrap($description_string, $indent, $description['type']);
    }


    /**
     * Description: Method to expose the data of a variable.
     * @param  string  $result
     */

    private function output(string $result)
    {
        if (self::$return == true) {
            return $result;
        } else {
            echo $result;
        }
    }

    /**
     * Description: Method to expose the data of a variable in plain text format ready to save to file.
     * @param $data
     * @return string|void
     * @throws ReflectionException
     */

    public function toPlain($data)
    {
        $result = $this->prepareOutput($data);
        if (self::$return == true) {
            return $result;
        } else {
            echo $result;
        }
    }

    /**
     * Description: Method to expose the data of a variable in Colored CLI Terminal format
     * @param $data
     */

    public function toTerminal($data): void
    {
        dump($data);
        echo 'Under Development toTerminal.<br>';
    }

    /**
     * Description: Method to expose the data of a variable in json format ready to send a response to frontend.
     * @param $data
     */

    public function toJson($data): void
    {
        dump($data);
        echo 'Under Development toJson.<br>';
    }
}


function dump(...$args)
{
    foreach ($args as $arg) {
        echo '<pre>';
        echo var_export($arg, true);
        echo '</pre>';
    }
}

