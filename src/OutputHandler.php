<?php

/**
 * BOH - Data Output Manager in PHP Development Environments.
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
 *
 */
class OutputHandler
{
    /**
     * Description: Store value if Running from Terminal/Command-Line Environment.
     * @var bool
     */
    private bool $ifTerminal;

    /**
     * Description: instantiate tool kit of Commons class.
     * @var Commons
     */
    private Commons $commons;

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
     * Description: Library configuration variable.
     * + Determines which of the web environments should run in the view.
     * - 'env': supported list
     *      - 'plain' (default)
     *      - 'web'
     *      - 'json'
     * @var string
     */
    private string $env = 'plain';

    /**
     * Description: Library configuration variable.
     * + Preload all the html, javascript in a separate web module from the original, it has no effect in cli environment.
     * - 'build': supported list
     *      - 'default' (default)
     *      - 'full'
     * @var string
     */
    private string $build = 'default';

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
    private string $css = 'default';

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
    private string $theme = 'default';

    /**
     * Description: Library configuration variable.
     * + set if we want to use indentation between name, values and comments.
     * - 'indent'
     *      - true (default)
     *      - false
     * @var bool
     */
    private bool $indent = true;


    /**
     * Description: Library configuration variable.
     * + set if we want to return data instead of exposing it.
     * - 'return'
     *      - true
     *      - false (default)
     * @var bool
     */
    private bool $return = false;


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
                if (isset($this->$key)) {
                    $this->$key = $value;
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
    public function chewed($data)
    {
        try {
            switch ($this->env) {
                case 'cli':
                    $this->ifTerminal ?: die('You are trying to use the toTerminal from a Web we recommend using toTerminal method.');
                    return $this->toTerminal($data);
                    break;
                case 'web':
                    !$this->ifTerminal ?: die('You are trying to use the toWeb from a CLI we recommend using toTerminal method.');
                    return $this->toWeb($data);
                    break;
                case 'json':
                    !$this->ifTerminal ?: die('You are trying to use the toJson from a CLI we recommend using toTerminal method.');
                    return $this->toJson($data);
                    break;
                default:
                    return $this->toPlain($data);
                    break;
            }
        } catch (ReflectionException $e) {
            die('invalid argument');
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
     * Description: Method to expose the data of a variable in Html format ready to insert and show in html body
     * @param $data
     */
    public function toWeb($data): void
    {
        dump($data);
        echo 'Under Development toWeb.<br>';
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

    /**
     * Description: Method to expose the data of a variable in plain text format ready to save to file.
     * @param $data
     * @return string|void
     * @throws ReflectionException
     */
    public function toPlain($data)
    {
        $deep               = ((int) ceil($this->commons->calculateDeepArray($data) + 1)) * 4;
        $description        = $this->analyzer->getVariableDescription($data);
        $indent             = $this->designer->getIndent($description, $this->indent, $deep);
        $description_string = $this->analyzer->getAnalysisDescription(
            $indent,
            $description
        );
        $result             = $this->temporalOutput($description['type'], $indent, $description_string);
        if ($this->return == true) {
            return $result;
        } else {
            return;
        }
    }

    public function temporalOutput(string $type, array $indent, string $description_string): string
    {
        $total_width        = $indent['total'] < $indent['min'] ? $indent['min'] : $indent['total'];
        $description_string = $this->commons->spaceJustify("<?php" . PHP_EOL . $description_string . "?>", $total_width);
        $title_text         = $this->getTitle($total_width, $type);
        $copyright          = $this->getCopyRight($total_width);
        $body_text          = highlight_string($description_string, true);
        return $title_text . '<br>' . $body_text . '<br>' . $copyright;
    }

    /**
     *
     * @param  int  $total_width
     * @param  string  $type
     * @return mixed
     */
    private function getTitle(int $total_width, string $type): string
    {
        $theme_applied = ' | Theme Applied: Default ';
        $title_text    = $theme_applied . '| OutputHandler of Given Variable | Type: ' . $type . ' | ';
        return $this->commons->fillCharBoth(
            $title_text,
            $total_width,
            '='
        );
    }

    /**
     *
     * @param  int  $total_width
     * @return mixed
     */
    private function getCopyRight(int $total_width): string
    {
        $copyright1 = $this->commons->fillCharBoth(
            ' [BOH] Basic Output Handler for PHP - Copyright 2020 - ' . date('Y') . ' ',
            $total_width,
            '='
        );
        /*
        $copyright2       = $this->commons->fillCharBoth(
            ' Open Source Project Developed by Icaros Net. S.A ',
            $total_width,
            '='
        );
        $copyright_indent = (int) floor(($total_width - 44) / 2);
        /*
        $copyright3       = $this->commons->repeatChar('=', $copyright_indent)
            . ' URL: <a href="https://github.com/IcarosNetSA/BOH-Basic-Output-Handler">IcarosNetSA/BOH-Basic-Output-Handler</a> '
            . $this->commons->repeatChar('=', (($copyright_indent * 2) < $total_width ? $copyright_indent + 1 : $copyright_indent));
        return $copyright1 . '<br>' . $copyright2 . '<br>' . $copyright3;
        */
        return $copyright1;
    }

}


function dump(...$args)
{
    foreach ($args as $arg) {
        echo '<pre>';
        echo var_dump($arg);
        echo '<pre>';
    }
}

