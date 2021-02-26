<?php

/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
 * PHP Version 7.4.
 *
 * @see https://github.com/arcanisgk/BOH-Basic-Output-Handler
 *
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 * @copyright 2020 - 2021 Marcus Bointon
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace IcarosNet\BOHBasicOutputHandler;

/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
 *
 * @author    Walter Nuñez (arcanisgk/original founder) <icarosnet@gmail.com>
 */

/**
 * Validation of php version.
 * strictly equal to or greater than 7.4
 * a minor version will kill any script.
 *
 */

if (!version_compare(phpversion(), '7.4', '>=')) {
    die('IcarosNet\BOHBasicOutputHandler requires PHP ver. 7.4 or higher');
}

/**
 * Validation of the environment of use.
 * support for web and cli environments
 *
 */

if (!defined('ENVIRONMENT_OUTPUT_HANDLER')) {
    define('ENVIRONMENT_OUTPUT_HANDLER', (IsCommandLineInterface() ? 'cli' : 'web'));
}

class OutputHandler
{

    /**
     * background color of output.
     * Options: empty (default), dependent on selected theme.
     *
     * @var string
     */
    public string $background = '';

    /**
     * theme selected by implementer.
     * Options: null (default), __construct update to 'default' or theme selected.
     * 'default','monokai','natural-flow','mauro-dark','x-space'
     *
     * @var string
     */
    public string $themeused;

    /**
     * capture the environment for usage in the class.
     * Options: empty (default), __construct update to 'ENVIRONMENT_OUTPUT_HANDLER'
     * constant or implementor defined environment.
     * 'cli','web'
     *
     * @var string
     */
    public string $defenv = '';


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
     * @param  string  $theme
     */
    public function __construct($theme = 'default')
    {
        $this->theme($theme);
        $this->defenv = ENVIRONMENT_OUTPUT_HANDLER;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->resetHighlight();
    }

    //Theme Code and Highlight

    public function resetHighlight()
    {
        ini_set("highlight.comment", "#FF9900");
        ini_set("highlight.default", "#0000BB");
        ini_set("highlight.html", "#000000");
        ini_set("highlight.keyword", "#007700; font-weight: bold");
        ini_set("highlight.string", "#DD0000");
    }

    /**
     * Call theme() for theme select by implementer.
     *
     * @param  string  $theme
     * Options: 'default' (default),'monokai','natural-flow','mauro-dark','x-space'
     */
    public function theme(string $theme = 'default'): void
    {
        $this->themeused = $theme;
        switch ($theme) {
            case 'x-space':
                $color            = ['043,128,041', '099,099,099', '128,128,128', '072,094,187', '221,079,079', '000,000,000'];
                $this->background = '000000';
                break;
            case 'mauro-dark':
                $color            = ['187,134,252', '250,250,250', '003,218,197', '255,204,255', '207,102,121', '018,018,018'];
                $this->background = '121212';
                break;
            case 'natural-flow':
                $color            = ['145,155,152', '30,156,107', '003,218,197', '006,156,004', '139,156,51', '004,041,003'];
                $this->background = '042903';
                break;
            case 'monokai':
                $color            = ['117,113,94', '255,255,255', '102,217,239', '249,038,114', '230,219,116', "039,040,034"];
                $this->background = '272822';
                break;
            default:
                $color            = ['255,095,000', '000,000,255', '000,000,000', '000,175,000', '255,000,000', '255,255,255'];
                $this->background = 'ffffff';
                break;
        }
        $this->setHighlightTheme($color);
        $this->setHighlightThemeCli($color);
    }

    /**
     * Sets color of theme selected for web design.
     *
     * @param  array  $color
     */
    private function setHighlightTheme(array $color): void
    {
        ini_set("highlight.comment", 'rgb(' . $color[0] . '); background-color: #' . $this->background);
        ini_set("highlight.default", 'rgb(' . $color[1] . '); background-color: #' . $this->background);
        ini_set("highlight.html", 'rgb(' . $color[2] . '); background-color: #' . $this->background);
        ini_set("highlight.keyword", 'rgb(' . $color[3] . "); font-weight: bold; background-color: #" . $this->background);
        ini_set("highlight.string", 'rgb(' . $color[4] . ');background-color: #' . $this->background);
    }

    /**
     * Sets color of theme selected for cli design.
     *
     * @param  array  $color
     */
    private function setHighlightThemeCli(array $color): void
    {
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
    private function highlightCode(string $string): string
    {
        return highlight_string("<?php \n#output of Variable:" . str_repeat(' ', 10)
            . '*****| Theme Used: ' . $this->themeused . " |*****\n" . $string . "\n?>", true);
    }

    /**
     * Convert normal string output of variable to
     * String highlight like php code for cli output
     *
     * @param  string  $string
     *
     * @return string
     */
    protected function highlightCodeCli(string $string): string
    {
        $bg     = $this->colorcli['background'];
        $string = '<?php' . PHP_EOL . $string . PHP_EOL . '?>';
        $string = $this->adjusterSpaceLine($string);
        $COLORS = $this->colorcli;
        $TOKENS = [
            T_AS                       => "as",
            T_CLOSE_TAG                => "tag",
            T_COMMENT                  => "comment",
            T_CONCAT_EQUAL             => "",
            T_CONSTANT_ENCAPSED_STRING => "string",
            T_CONTINUE                 => "keyword",
            T_DOUBLE_ARROW             => "variable",
            T_ECHO                     => "keyword",
            T_ELSE                     => "keyword",
            T_FILE                     => "magic",
            T_FOREACH                  => "keyword",
            T_FUNCTION                 => "keyword",
            T_IF                       => "keyword",
            T_IS_EQUAL                 => "",
            T_ISSET                    => "keyword",
            T_LIST                     => "keyword",
            T_OPEN_TAG                 => "tag",
            T_RETURN                   => "keyword",
            T_STATIC                   => "keyword",
            T_VARIABLE                 => "variable",
            T_WHITESPACE               => "",
            T_LNUMBER                  => "function",
            T_DNUMBER                  => "function",
            T_OBJECT_CAST              => "variable",
            T_STRING                   => "function",
            T_INLINE_HTML              => "",
        ];
        $output = "";
        foreach (token_get_all($string) as $token) {
            if (is_string($token)) {
                $output .= $bg . $token . "\033[0m";
                continue;
            }
            list($t, $str) = $token;
            if ($t == T_STRING) {
                if (function_exists($str)) {
                    $output .= $bg . sprintf($COLORS["function"], $str) . "\033[0m";
                } else {
                    if (defined($str)) {
                        $output .= $bg . sprintf($COLORS["function"], $str) . "\033[0m";
                    } else {
                        $output .= $bg . sprintf($COLORS["function"], $str) . "\033[0m";
                    }
                }
            } else {
                if (isset($TOKENS[$t])) {
                    $output .= $bg . sprintf($COLORS[$TOKENS[$t]], $str) . "\033[0m";
                } else {
                    $output .= $bg . sprintf("<%s '%s'>", token_name($t), $str) . "\033[0m";
                }
            }
        }
        return $output;
    }

    /**
     * space adjuster at the end of the line for full background coverage in cli
     *
     * @param  string  $string
     *
     * @return string
     */
    private function adjusterSpaceLine(string $string): string
    {
        $info      = shell_exec('MODE 2> null') ?? shell_exec('tput cols');
        $widthreal = 80;
        if (strlen($info) > 5) {
            preg_match('/CON.*:(\n[^|]+?){3}(?<cols>\d+)/', $info, $match);
            $widthreal = $match['cols'] ?? 80;
        }
        $width     = (int) $widthreal - 10;
        $stringarr = preg_split('/\r\n|\r|\n/', rtrim($string));
        $numline   = count($stringarr);
        $maxlen    = max(array_map(function ($el) {
            return mb_strlen($el);
        }, $stringarr));
        $longest   = ($maxlen > $width ? $maxlen : $width);
        if ($maxlen > $widthreal) {
            echo 'Oops, your terminal window is not wide enough to display the information correctly.' . PHP_EOL .
                'If you can increase the amount of characters per line (' . ($maxlen + 10) . ') it would work correctly.';
            exit;
        }
        $string = '';
        $count  = 1;
        foreach ($stringarr as $key => $line) {
            $lenline = mb_strlen($line);
            $string  .= $line . str_repeat(' ', $longest - $lenline) . ($count < $numline ? PHP_EOL : '');
            $count++;
        }
        return $string;
    }

    /**
     * CSS applicator for web design.
     *
     * @param  string  $string
     *
     * @return string
     */
    private function applyCss(string $string): string
    {
        $bg    = '#' . $this->background;
        $class = mt_rand();
        return '<style>.outputhandler-' . $class . '{background-color: ' . $bg . '; padding: 8px;border-radius: 8px; margin: 5px}</style>
                    <div class="outputhandler-' . $class . '">' . $string . '</div>';
    }

    /**
     * environment checker; if the implementer is wrong;
     * the library will abort any execution immediately
     * and display an error message stating that it has happened.
     *
     * @param  null|string  $env
     *
     * @return string
     */
    private function checkEnv($env): string
    {
        $iscli = IsCommandLineInterface();
        $env   = ($env == null ? $this->defenv : $env);
        if ($iscli && $env == 'wb') {
            echo 'error: you are trying to run output() method from CLI and it is not supported, use OutputCli() or AdvanceOutput() with CLI argument  method instead.';
            exit;
        } elseif (!$iscli && $env == 'cli') {
            echo 'error: you are trying to run OutputCli() method from web browser and it is not supported, use Output() or AdvanceOutput() with HTML argument method instead.';
            exit;
        }
        return $env;
    }

    /**
     * Check and Execute the request to show the formatted data.
     *
     * @param  mixed  $var
     * @param  null|string  $env
     * @param  bool  $retrieve
     *
     * @return void|string
     */
    public function output($var, $env = null, $retrieve = false)
    {
        $env = $this->checkEnv($env);
        if ($env == 'web') {
            $string = $this->outputWb($var, $retrieve);
        } elseif ($env == 'cli') {
            $string = $this->outputCli($var, $retrieve);
        } else {
            $string = $this->outputWb($var, $retrieve);
        }
        if ($retrieve) {
            return $string;
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
    public function outputWb($var, $retrieve = false)
    {
        $indents = $this->getIndent($var);
        $string  = $this->analyzeVariable($var, $indents);
        $string  = $this->highlightCode($string);
        $string  = $this->applyCss($string);
        $this->resetHighlight();
        return ($retrieve ? $string : $this->outView($string));
    }

    /**
     * Check and Execute the request to show the formatted data for cli environment.
     *
     * @param  mixed  $var
     * @param  bool  $retrieve
     *
     * @return void|string
     */
    public function outputCli($var, $retrieve = false)
    {
        $indents = $this->getIndent($var);
        $string  = $this->analyzeVariable($var, $indents);
        $string  = $this->highlightCodeCli($string);
        $this->resetHighlight();
        return ($retrieve ? $string : $this->outView($string));
    }

    /**
     * Evaluates the indentation that the values and
     * comments should have in the construction of the output
     *
     * @param  mixed  $var
     *
     * @return array
     */
    private function getIndent($var): array
    {
        $data    = $var;
        $indents = ['key' => 0, 'val' => 0];
        if (is_array($data) || is_object($data)) {
            array_walk_recursive($data, function (&$value) {
                $value = is_object($value) ? (array) $value : $value;
            });
            $deep = ($this->calcDeepArray($data) + 1) * 4;
            array_walk_recursive($data, function ($value, $key) use (&$indents) {
                $indents['key'] = ($indents['key'] >= mb_strlen($key)) ? $indents['key'] : mb_strlen($key);
                if (!is_array($value) && !is_object($value) && !is_resource($value)) {
                    $indents['val'] = ($indents['val'] >= mb_strlen($value)) ? $indents['val'] : mb_strlen($value);
                }
            }, $indents);
            $indents['key'] += $deep;
            $indents['val'] += $deep / 2;
        } else {
            $indents = ['key' => mb_strlen('variable'), 'val' => mb_strlen($data)];
        }
        return $indents;
    }

    /**
     * Calculates how many nodes deep the passed variable has if it is an array or object.
     * note: it does not calculate the number of total nodes.
     *
     * @param  array  $array
     *
     * @return int
     */
    private function calcDeepArray(array $array): int
    {
        $max_depth = 0;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $depth = $this->calcDeepArray($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    /**
     * This should parse each variable passed and build the output string,
     * similar to var_dump or var_export.
     *
     * @param  mixed  $var
     * @param  array  $indents
     *
     * @return string
     */
    protected function analyzeVariable($var, array $indents): string
    {
        $varname     = 'variable';
        $pretty      = function ($indents, $varlentitle, $v = '', $c = " ", $in = 0, $k = null) use (&$pretty) {
            $r = '';
            if (in_array(gettype($v), array('object', 'array'))) {
                $lenname = mb_strlen("'$k'");
                $lenkeys = $indents['key'] - $in - $lenname;
                if ($lenkeys < 0) {
                    $lenkeys = 0;
                }
                $eval   = $this->evaluateVariable($v);
                $v      = (array) $v;
                $lenkey = $indents['val'] - mb_strlen($eval['val']) + 1;
                if (empty($v)) {
                    $r .= ($in != 0 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "'$k'"
                            . str_repeat($c, $lenkeys) . "=> " . $eval['val'] . "[],"
                            . str_repeat(" ", $lenkey - 6) . "// "
                            . $eval['desc']) . (empty($v) ? '' : PHP_EOL);
                } else {
                    $r .= ($in != 0 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "'$k'"
                            . str_repeat($c, $lenkeys) . "=> " . $eval['val'] . "["
                            . str_repeat(" ", $lenkey - 4) . "// "
                            . $eval['desc']) . (empty($v) ? '' : PHP_EOL);
                    foreach ($v as $sk => $vl) {
                        $r .= $pretty($indents, $varlentitle, $vl, $c, $in + 4, $sk) . PHP_EOL;
                    }
                    $r .= (empty($v) ? '],' : ($in != 0 ? str_repeat($c, $in / 2) : '')
                        . (is_null($v) ? '' : str_repeat($c, $in / 2) . "],"));
                }
            } else {
                $lenkey = $indents['key'] - mb_strlen("'$k'") - $in;
                if ($lenkey < 0) {
                    $lenkey = 0;
                }
                $eval   = $this->evaluateVariable($v);
                $lenval = $indents['val'] - (mb_strlen("'" . $eval['val'] . "'"));
                if ($lenval < 0) {
                    $lenval = 0;
                }
                $r .= ($in != -1 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "'$k'"
                        . str_repeat($c, $lenkey) . '=> ') . $eval['val']
                    . str_repeat(" ", $lenval) . '// ' . $eval['desc'];
            }
            return $r;
        };
        $varlentitle = mb_strlen('$' . $varname);
        if (in_array(gettype($var), array('object', 'array'))) {
            $string = '$' . $varname . str_repeat(" ", ($indents['key'] - $varlentitle)) . '= ['
                . str_repeat(" ", $indents['val'] - 2) . '// main array node'
                . rtrim($pretty($indents, $varlentitle, $var), ',') . ';';
        } else {
            $eval   = $this->evaluateVariable($var);
            $string = '$' . $varname . str_repeat(" ", $indents['key']) . '=' . $eval['val'] . ';'
                . str_repeat(" ", $indents['val'] - 1) . '// ' . $eval['desc'];
        }
        return $string;
    }

    /**
     * This should analyze each variable passed indicate the value and description of it.
     * note: the description is a rich text.
     *
     * @param  mixed  $var
     *
     * @return array
     */
    protected function evaluateVariable($var): array
    {
        if (null === $var || 'null' === $var || 'NULL' === $var) {
            if (is_string($var)) {
                return ['val' => "'null'", 'desc' => 'null value string.'];
            } else {
                return ['val' => 'null', 'desc' => 'null value.'];
            }
        }

        if (is_array($var)) {
            return ['val' => "", 'desc' => 'array node.'];
        }

        if (in_array($var, ["true", "false", true, false], true)) {
            if (is_string($var)) {
                return ['val' => "'" . $var . "'", 'desc' => 'string value boolean ' . $var . '.'];
            } else {
                return ['val' => ($var ? 'true' : 'false'), 'desc' => 'boolean value ' . ($var ? 'true' : 'false') . '.'];
            }
        }

        if (is_object($var)) {
            ob_start();
            var_dump($var);
            $string = explode('{', ob_get_clean());
            return ['val' => '(object) ', 'desc' => rtrim(reset($string)) . '.'];
        }

        if ((int) $var == $var && is_numeric($var)) {
            if (is_string($var)) {
                return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') integer value string.'];
            } else {
                return ['val' => $var, 'desc' => '(' . mb_strlen($var) . ') integer value.'];
            }
        }

        if ((float) $var == $var && is_numeric($var)) {
            if (is_string($var)) {
                return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') float value string.'];
            } else {
                return ['val' => $var, 'desc' => '(' . mb_strlen($var) . ') float value.'];
            }
        }

        ob_start();
        var_dump($var);
        $string = ob_get_clean();
        if (mb_strpos($string, 'resource') !== false) {
            return ['val' => 'resource', 'desc' => rtrim($string) . '.'];
        } elseif (mb_strpos($string, 'of type ') !== false) {
            return ['val' => 'resource', 'desc' => rtrim($string) . '.'];
        }
        unset($string);

        if (mb_strpos($var, ' ') !== false && mb_strpos($var, ':') !== false && mb_strpos($var, '-') !== false) {
            $datetime = explode(" ", $var);
            $validate = 0;
            foreach ($datetime as $value) {
                if ($this->validateDate($value)) {
                    $validate++;
                }
            }
            if ($validate >= 2) {
                return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value datetime.'];
            }
        }

        if ($this->validateDate($var) && mb_strpos($var, ':') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value time.'];
        }

        if ($this->validateDate($var) && mb_strlen($var) >= 8 && mb_strpos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value date.'];
        }

        if ($this->validateDate($var) && mb_strlen($var) >= 8 && mb_strpos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value date.'];
        }

        if (is_string($var)) {
            $arr           = $this->splitStrToUnicode($var);
            $currencylist  = [
                '¤', '$', '¢', '£', '¥', '₣', '₤', '₧', '€', '₹', '₩', '₴',
                '₯', '₮', '₰', '₲', '₱', '₳', '₵', '₭', '₪', '₫', '₠', '₡', '₢', '₥', '₦',
                '₨', '₶', '₷', '₸', '₺', '₻', '₼', '₽', '₾', '₿'
            ];
            $currencycheck = [];
            foreach ($arr as $char) {
                if (in_array($char, $currencylist, true)) {
                    $currencycheck[] = $char;
                }
            }
            if (!empty($currencycheck)) {
                return [
                    'val' => "'" . $var . "'", 'desc' => 'string/amount value related to currency ('
                        . implode(',', $currencycheck) . ').'
                ];
            }
        }

        if (is_string($var)) {
            return ['val' => "'" . $var . "'", 'desc' => 'string value of ' . mb_strlen($var) . ' character.'];
        }

        return ['val' => 'unknow', 'desc' => 'unknow'];
    }

    /**
     * This should validate Date String.
     *
     * @param  string  $date
     *
     * @return bool
     */
    private function validateDate(string $date): bool
    {
        return (strtotime($date) !== false);
    }

    /**
     * This should cut the strings in unicode format.
     *
     * @param  string  $str
     * @param  int  $length  default 1
     *
     * @return array
     */
    private function splitStrToUnicode(string $str, $length = 1): array
    {
        $tmp = preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY);
        if ($length > 1) {
            $chunks = array_chunk($tmp, $length);
            foreach ($chunks as $i => $chunk) {
                $chunks[$i] = join('', (array) $chunk);
            }
            $tmp = $chunks;
        }
        return $tmp;
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

/**
 * check if runtime environment is CLI
 *
 * @return bool
 */
function IsCommandLineInterface(): bool
{
    return (php_sapi_name() === 'cli');
}