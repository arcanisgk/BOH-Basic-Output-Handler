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


class OutputDesigner extends Validation
{

    /**
     * theme selected by implementer.
     * Options: null (default), __construct update to 'default' or theme selected.
     * 'default','monokai','natural-flow','mauro-dark','x-space'
     *
     * @var string
     */
    protected string $theme = 'default';

    /**
     * List of default themes colors.
     *
     * @var array
     */
    const THEMES = [
        'x-space'      => ['043,128,041', '099,099,099', '128,128,128', '072,094,187', '221,079,079', '000,000,000'],
        'mauro-dark'   => ['187,134,252', '250,250,250', '003,218,197', '255,204,255', '207,102,121', '018,018,018'],
        'natural-flow' => ['145,155,152', '030,156,107', '003,218,197', '006,156,004', '139,156,051', '004,041,003'],
        'monokai'      => ['117,113,094', '255,255,255', '102,217,239', '249,038,114', '230,219,116', "039,040,034"],
        'default'      => ['255,095,000', '000,000,255', '000,000,000', '000,175,000', '255,000,000', '255,255,255'],
    ];

    /**
     * definition of colors for implementation in CLI.
     *
     * @var array
     */
    public array $colorcli = [
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
     * Constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->getTheme();
    }

    /**
     * Destructor.
     *
     */
    public function __destruct()
    {
        $this->resetHighlight();
    }

    /**
     * Call for reset of Theme colors in Web.
     */
    public function resetHighlight(): void
    {
        ini_set("highlight.comment", "#FF9900");
        ini_set("highlight.default", "#0000BB");
        ini_set("highlight.html", "#000000");
        ini_set("highlight.keyword", "#007700; font-weight: bold");
        ini_set("highlight.string", "#DD0000");
    }

    /**
     * theme setter; if the implementer need it;
     *
     * @param  string  $theme
     *
     */
    public function setTheme(string $theme = 'default')
    {
        $this->theme = $theme;
    }

    /**
     * Call theme() for theme select by implementer.
     *
     * @param  string  $theme
     * Options: 'default' (default),'monokai','natural-flow','mauro-dark','x-space'
     */
    public function getTheme(string $theme = 'default'): void
    {
        $color = isset(self::THEMES[$theme]) ? self::THEMES[$theme] : self::THEMES['default'];
        $this->setHighlightTheme($color);
    }

    /**
     * Sets color of theme selected for cli/web design.
     *
     * @param  array  $color
     */
    private function setHighlightTheme(array $color): void
    {
        if ($this->env == 'web') {
            ini_set("highlight.comment", 'rgb(' . $color[0] . '); background-color: rgb(' . $color[5] . ');');
            ini_set("highlight.default", 'rgb(' . $color[1] . '); background-color: rgb(' . $color[5] . ');');
            ini_set("highlight.html", 'rgb(' . $color[2] . '); background-color: rgb(' . $color[5] . ');');
            ini_set("highlight.keyword", 'rgb(' . $color[3] . "); font-weight: bold; background-color: rgb(" . $color[5] . ');');
            ini_set("highlight.string", 'rgb(' . $color[4] . '); background-color: rgb(' . $color[5] . ');');
        } else {
            $this->colorcli['comment']    = "\033[38;2;" . $this->colorRGBforCLI($color[0]) . "m%s\033[0m";
            $this->colorcli['constant']   = "\033[38;2;" . $this->colorRGBforCLI($color[4]) . "m%s\033[0m";
            $this->colorcli['function']   = "\033[38;2;" . $this->colorRGBforCLI($color[1]) . "m%s\033[0m";
            $this->colorcli['keyword']    = "\033[38;2;" . $this->colorRGBforCLI($color[3]) . "m%s\033[0m";
            $this->colorcli['magic']      = "\033[38;2;" . $this->colorRGBforCLI($color[1]) . "m%s\033[0m";
            $this->colorcli['string']     = "\033[38;2;" . $this->colorRGBforCLI($color[4]) . "m%s\033[0m";
            $this->colorcli['tag']        = "\033[38;2;" . $this->colorRGBforCLI($color[1]) . "m%s\033[0m";
            $this->colorcli['variable']   = "\033[38;2;" . $this->colorRGBforCLI($color[3]) . "m%s\033[0m";
            $this->colorcli['html']       = "\033[38;2;" . $this->colorRGBforCLI($color[2]) . "m%s\033[0m";
            $this->colorcli['background'] = "\033[48;2;" . $this->colorRGBforCLI($color[5]) . "m";
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
        return str_replace(',', ';', $color);
    }

    /**
     * Convert normal string output of variable to
     * String highlight like php code for web output
     *
     * @param  string  $string
     *
     * @return string
     */
    protected function highlightCode(string $string): string
    {
        //These are the data that the variable contains:
        /* \n#output of Variable:" . $this->repeatChar(' ', 10) . '*****| Theme Used: ' . $this->theme . " |*****\n" . $string . "\n?>*/
        

        return highlight_string("", true);
    }
}