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

/**
 * ThemeController Class.
 *
 */
class ThemeController
{
    /**
     * List of default themes colors.
     * @var array
     * comment | default | html | keyword | string | bg
     */

    const THEMES = [
        'space'   => ['043,128,041', '099,099,099', '128,128,128', '072,094,187', '221,079,079', '000,000,000'],
        'mauro'   => ['187,134,252', '250,250,250', '003,218,197', '255,204,255', '207,102,121', '018,018,018'],
        'natural' => ['145,155,152', '030,156,107', '003,218,197', '006,156,004', '139,156,051', '004,041,003'],
        'monokai' => ['117,113,094', '255,255,255', '102,217,239', '249,038,114', '230,219,116', '039,040,034'],
        'vscode'  => ['080,102,134', '226,102,116', '213,157,246', '246,246,245', '213,157,246', '038,045,062'],
        'red'     => ['254,172,002', '189,063,075', '102,217,239', '254,254,254', '189,008,025', '072,000,017'],
        'gray'    => ['117,113,094', '255,255,255', '102,217,239', '169,169,169', '139,139,139', '049,049,049'],
        'dark'    => ['255,095,000', '139,139,139', '000,000,000', '000,175,000', '255,000,000', '036,036,036'],
        'default' => ['255,095,000', '000,000,255', '000,000,000', '000,175,000', '255,000,000', '255,255,255'],
    ];

    /**
     * definition of colors for implementation in CLI.
     *
     * @var array
     */

    public array $color_cli = [
        "comment"    => '',
        "constant"   => '',
        "function"   => '',
        "keyword"    => '',
        "magic"      => '',
        "string"     => '',
        "tag"        => '',
        "variable"   => '',
        "html"       => '',
        ""           => "%s",
        "background" => '',
    ];

    /**
     * Description: Library configuration variable.
     * + Determines which of the web environments should run in the view.
     * - 'env': supported list
     *      - 'plain' (default)
     *      - 'web'
     *      - 'json'
     * @var string
     */
    public string $env = 'plain';

    private Commons $commons;

    public function __construct()
    {

        //$this->commons = new Commons();
    }

    /**
     * Call theme() for theme select by implementer.
     *
     * @param  string  $theme
     * Options: 'default' (default),'monokai','natural-flow','mauro-dark','x-space'
     */
    public function setTheme(string $theme = 'default', string $env): void
    {
        $color = (self::THEMES[$theme] ?? self::THEMES['default']);
        $this->setHighlightTheme($color);
    }

    /**
     * Sets color of theme selected for cli/web design.
     *
     * @param  array  $color
     */
    private function setHighlightTheme(array $color): void
    {

        if ($this->env == 'cli') {
            $this->color_cli['comment']    = "\033[38;2;" . $this->colorRGBforCLI($color[0]) . "m%s\033[0m";
            $this->color_cli['constant']   = "\033[38;2;" . $this->colorRGBforCLI($color[4]) . "m%s\033[0m";
            $this->color_cli['function']   = "\033[38;2;" . $this->colorRGBforCLI($color[1]) . "m%s\033[0m";
            $this->color_cli['keyword']    = "\033[38;2;" . $this->colorRGBforCLI($color[3]) . "m%s\033[0m";
            $this->color_cli['magic']      = "\033[38;2;" . $this->colorRGBforCLI($color[1]) . "m%s\033[0m";
            $this->color_cli['string']     = "\033[38;2;" . $this->colorRGBforCLI($color[4]) . "m%s\033[0m";
            $this->color_cli['tag']        = "\033[38;2;" . $this->colorRGBforCLI($color[1]) . "m%s\033[0m";
            $this->color_cli['variable']   = "\033[38;2;" . $this->colorRGBforCLI($color[3]) . "m%s\033[0m";
            $this->color_cli['html']       = "\033[38;2;" . $this->colorRGBforCLI($color[2]) . "m%s\033[0m";
            $this->color_cli['background'] = "\033[48;2;" . $this->colorRGBforCLI($color[5]) . "m";
        } else {
            ini_set("highlight.comment", 'rgb(' . $color[0] . '); background-color: rgb(' . $color[5] . '); font-style: italic; white-space: nowrap;');
            ini_set("highlight.default", 'rgb(' . $color[1] . '); background-color: rgb(' . $color[5] . '); white-space: nowrap;');
            ini_set("highlight.html", 'rgb(' . $color[2] . '); background-color: rgb(' . $color[5] . '); white-space: nowrap;');
            ini_set("highlight.keyword", 'rgb(' . $color[3] . "); font-weight: bold; background-color: rgb(" . $color[5] . '); white-space: nowrap;');
            ini_set("highlight.string", 'rgb(' . $color[4] . '); background-color: rgb(' . $color[5] . '); white-space: nowrap;');
        }
    }

    /**
     * Convert RGB color String from web standard to ANSI color .
     *
     * @param  string  $color
     *
     * @return string
     */
    private function colorRGBforCLI(string $color): string
    {
        return strtr($color, ',', ';');
    }


    /**
     * Convert RGB color String from web standard to ANSI color .
     *
     * @param  string  $theme
     *
     * @return string
     */
    public function getBackGround($theme): string
    {
        return self::THEMES[$theme][5];
    }
}