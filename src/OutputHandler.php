<?php

namespace IcarosNet\BOHBasicOutputHandler;

if (!version_compare(phpversion(), '7.4', '>=')) {
    die('IcarosNet\BOHBasicOutputHandler requires PHP ver. 7.4 or higher');
}

if (!defined('ENVIRONMENT_OUTPUT_HANDLER')) {
    define('ENVIRONMENT_OUTPUT_HANDLER', (IsCommandLineInterface() ? 'cli' : 'web'));
}

class OutputHandler
{
    public string $background = '';
    public string $themeused;
    public string $defenv = '';
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

    public function __construct($theme = 'default')
    {
        $this->Theme($theme);
        $this->defenv = ENVIRONMENT_OUTPUT_HANDLER;
    }

    public function __destruct()
    {
        $this->ResetHighlight();
    }

    //Theme Code and Highlight

    public function ResetHighlight()
    {
        ini_set("highlight.comment", "#FF9900");
        ini_set("highlight.default", "#0000BB");
        ini_set("highlight.html", "#000000");
        ini_set("highlight.keyword", "#007700; font-weight: bold");
        ini_set("highlight.string", "#DD0000");
    }

    public function Theme(string $theme = 'default')
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
                $this->background = 'white';
                break;
        }
        $this->UpdateInitSetHighlight($color);
        $this->UpdateInitSetHighlightCli($color);
    }

    private function UpdateInitSetHighlight($color)
    {
        ini_set("highlight.comment", 'rgb(' . $color[0] . ')');
        ini_set("highlight.default", 'rgb(' . $color[1] . ')');
        ini_set("highlight.html", 'rgb(' . $color[2] . ')');
        ini_set("highlight.keyword", 'rgb(' . $color[3] . "); font-weight: bold");
        ini_set("highlight.string", 'rgb(' . $color[4] . ')');
    }

    private function UpdateInitSetHighlightCli($color)
    {
        $this->colorcli['comment']    = "\033[38;2;" . $this->RGBforCLI($color[0]) . "m%s\033[0m";
        $this->colorcli['constant']   = "\033[38;2;" . $this->RGBforCLI($color[4]) . "m%s\033[0m";
        $this->colorcli['function']   = "\033[38;2;" . $this->RGBforCLI($color[1]) . "m%s\033[0m";
        $this->colorcli['keyword']    = "\033[38;2;" . $this->RGBforCLI($color[3]) . "m%s\033[0m";
        $this->colorcli['magic']      = "\033[38;2;" . $this->RGBforCLI($color[1]) . "m%s\033[0m";
        $this->colorcli['string']     = "\033[38;2;" . $this->RGBforCLI($color[4]) . "m%s\033[0m";
        $this->colorcli['tag']        = "\033[38;2;" . $this->RGBforCLI($color[1]) . "m%s\033[0m";
        $this->colorcli['variable']   = "\033[38;2;" . $this->RGBforCLI($color[3]) . "m%s\033[0m";
        $this->colorcli['html']       = "\033[38;2;" . $this->RGBforCLI($color[2]) . "m%s\033[0m";
        $this->colorcli['background'] = "\033[48;2;" . $this->RGBforCLI($color[5]) . "m";
    }

    private function RGBforCLI($color)
    {
        return str_replace(',', ';', $color);
    }

    private function HighlightCode(string $string): string
    {
        return highlight_string("<?php \n#output of Variable:" . str_repeat(' ', 10)
            . '*****| Theme Used: ' . $this->themeused . " |*****\n" . $string . "\n?>", true);
    }

    private function HighlightCodeCli(string $string): string
    {
        $bg     = $this->colorcli['background'];
        $string = '<?php' . PHP_EOL . $string . PHP_EOL . '?>';
        $string = $this->CoverforBackground($string);
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


    private function CoverforBackground(string $string): string
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

    private function ApplyCss(string $string): string
    {
        $bg    = '#' . $this->background;
        $class = mt_rand();
        return '<style>.outputhandler-' . $class . '{background-color: ' . $bg . '; padding: 8px;border-radius: 8px;}</style>
                    <div class="outputhandler-' . $class . '">' . $string . '</div>';
    }

    //core Analysis or OuputHandler

    private function CheckEnv($env): string
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

    public function Output($var, $env = null, $retrive = false)
    {
        $env = $this->CheckEnv($env);
        if ($env == 'web') {
            $string = $this->OutputWb($var, $retrive);
        } elseif ($env == 'cli') {
            $string = $this->OutputCli($var, $retrive);
        } else {
            $string = $this->OutputWb($var, $retrive);
        }
        if ($retrive) {
            return $string;
        }
    }

    public function OutputWb($var, $retrive = false)
    {
        $indents = $this->GetIndent($var);
        $string  = $this->GetString($var, $indents);
        $string  = $this->HighlightCode($string);
        $string  = $this->ApplyCss($string);
        $this->ResetHighlight();
        return ($retrive ? $string : $this->OutView($string));
    }

    public function OutputCli($var, $retrive = false)
    {
        $indents = $this->GetIndent($var);
        $string  = $this->GetString($var, $indents);
        $string  = $this->HighlightCodeCli($string);
        $this->ResetHighlight();
        return ($retrive ? $string : $this->OutView($string));
    }

    private function GetIndent($var): array
    {
        $data    = $var;
        $indents = ['key' => 0, 'val' => 0];
        if (is_array($data) || is_object($data)) {
            array_walk_recursive($data, function (&$value) {
                $value = is_object($value) ? (array) $value : $value;
            });
            $deep = ($this->CalcDeepArray($data) + 1) * 4;
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

    private function CalcDeepArray(array $array): int
    {
        $max_depth = 0;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $depth = $this->CalcDeepArray($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    private function GetString($var, array $indents): string
    {
        return $this->AnalysisVariable('variable', $var, $indents);
    }

    private function AnalysisVariable(string $varname, $var, array $indents): string
    {
        $pretty      = function ($indents, $varlentitle, $v = '', $c = " ", $in = 0, $k = null) use (&$pretty) {
            $r = '';
            if (in_array(gettype($v), array('object', 'array'))) {
                $lenname = mb_strlen("'$k'");
                $lenkeys = $indents['key'] - $in - $lenname;
                if ($lenkeys < 0) {
                    $lenkeys = 0;
                }
                $eval   = $this->EvaluateVariable($v);
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
                $eval   = $this->EvaluateVariable($v);
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
            return '$' . $varname . str_repeat(" ", ($indents['key'] - $varlentitle)) . '= ['
                . str_repeat(" ", $indents['val'] - 2) . '// main array node'
                . rtrim($pretty($indents, $varlentitle, $var), ',') . ';';
        } else {
            $eval = $this->EvaluateVariable($var);
            return '$' . $varname . str_repeat(" ", $indents['key']) . '=' . $eval['val'] . ';'
                . str_repeat(" ", $indents['val'] - 1) . '// ' . $eval['desc'];
        }
    }

    public function EvaluateVariable($var): array
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

        ob_start();
        var_dump($var);
        $string = ob_get_clean();
        if (is_object($var)) {
            $string = explode('{', $string);
            return ['val' => '(object) ', 'desc' => rtrim($string[0]) . '.'];
        }
        unset($string);

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
                if ($this->ValidateDate($value)) {
                    $validate++;
                }
            }
            if ($validate >= 2) {
                return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value datetime.'];
            }
        }

        if ($this->ValidateDate($var) && mb_strpos($var, ':') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value time.'];
        }

        if ($this->ValidateDate($var) && mb_strlen($var) >= 8 && mb_strpos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value date.'];
        }

        if ($this->ValidateDate($var) && mb_strlen($var) >= 8 && mb_strpos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value date.'];
        }

        if (is_string($var)) {
            $arr           = $this->StrSplitUnicode($var);
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

    private function ValidateDate(string $date): bool
    {
        return (strtotime($date) !== false);
    }

    private function StrSplitUnicode(string $str, $length = 1): array
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

    private function OutView(string $string)
    {
        echo $string;
    }
}

function IsCommandLineInterface(): bool
{
    return (php_sapi_name() === 'cli');
}