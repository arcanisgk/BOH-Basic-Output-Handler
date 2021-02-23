<?php

namespace IcarosNet\BOHBasicOutputHandler;


class OutputHandler
{
    public string $background = 'null';
    public string $themeused;

    public function __construct($theme = 'default')
    {
        $this->themeused = $theme;
        $this->themeselector($theme);
    }

    private function themeselector(string $theme)
    {
        switch ($theme) {
            case 'x-space':
                $this->background = 'black';
                ini_set("highlight.comment", "#2B8029");
                ini_set("highlight.default", "#636363");
                ini_set("highlight.html", "#808080");
                ini_set("highlight.keyword", "#485EBB; font-weight: bold");
                ini_set("highlight.string", "#DD4F4F");
                break;
            case 'mauro-dark':
                $this->background = '#121212';
                ini_set("highlight.comment", "#BB86FC");
                ini_set("highlight.default", "#FAFAFA");
                ini_set("highlight.html", "#03DAC5");
                ini_set("highlight.keyword", "#FF7597; font-weight: bold");
                ini_set("highlight.string", "#CF6679");
                break;
            case 'natural-flow':
                $this->background = '#042903';
                ini_set("highlight.comment", "#919B98");
                ini_set("highlight.default", "#1E9C6B");
                ini_set("highlight.html", "#03DAC5");
                ini_set("highlight.keyword", "#069C04; font-weight: bold");
                ini_set("highlight.string", "#8B9C33");
                break;
            case 'monokai':
                $this->background = '#272822';
                ini_set("highlight.comment", "#70716A");
                ini_set("highlight.default", "#FAFAFA");
                ini_set("highlight.html", "#03DAC5");
                ini_set("highlight.keyword", "#F92672; font-weight: bold");
                ini_set("highlight.string", "#A39249");
                break;
        }
    }

    public function output($varname)
    {
        $indents = $this->getIndent($varname);
        $string  = $this->GetString($varname, $indents);
        $string  = $this->HighlightCode($string);
        $this->OutView($string);
    }

    /*
    public function AdvanceOutput($var)
    {
        echo 'hello world';
    }
    */

    private function getIndent(string $varname): array
    {
        $data    = $GLOBALS[$varname];
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
            $indents = ['key' => mb_strlen($varname), 'val' => mb_strlen($data)];
        }
        return $indents;
    }

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

    private function GetString(string $varname, array $indents): string
    {
        $var = &$GLOBALS[$varname];
        return $this->AnalVariable($varname, $var, $indents);
    }

    private function AnalVariable(string $varname, $var, array $indents): string
    {
        $pretty      = function ($indents, $varlentitle, $v = '', $c = " ", $in = 0, $k = null) use (&$pretty) {
            $r = '';
            if (in_array(gettype($v), array('object', 'array'))) {
                $lenname = mb_strlen("'$k'");
                $lenkeys = $indents['key'] - $in - $lenname;
                if ($lenkeys < 0) {
                    $lenkeys = 0;
                }
                $eval   = $this->EvalVariable($v);
                $v      = (array) $v;
                $lenkey = $indents['val'] - mb_strlen($eval['val']) + 1;
                if (empty($v)) {
                    $r .= ($in != 0 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "'$k'" . str_repeat($c, $lenkeys) . "=> " . $eval['val'] . "[]," . str_repeat(" ", $lenkey - 6) . "// " . $eval['desc']) . (empty($v) ? '' : PHP_EOL);
                } else {
                    $r .= ($in != 0 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "'$k'" . str_repeat($c, $lenkeys) . "=> " . $eval['val'] . "[" . str_repeat(" ", $lenkey - 4) . "// " . $eval['desc']) . (empty($v) ? '' : PHP_EOL);
                    foreach ($v as $sk => $vl) {
                        $r .= $pretty($indents, $varlentitle, $vl, $c, $in + 4, $sk) . PHP_EOL;
                    }
                    $r .= (empty($v) ? '],' : ($in != 0 ? str_repeat($c, $in / 2) : '') . (is_null($v) ? '' : str_repeat($c, $in / 2) . "],"));
                }
            } else {
                $lenkey = $indents['key'] - mb_strlen("'$k'") - $in;
                if ($lenkey < 0) {
                    $lenkey = 0;
                }
                $eval   = $this->EvalVariable($v);
                $lenval = $indents['val'] - (mb_strlen("'" . $eval['val'] . "'"));
                if ($lenval < 0) {
                    $lenval = 0;
                }
                $r .= ($in != -1 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "'$k'" . str_repeat($c, $lenkey) . '=> ') . $eval['val'] . str_repeat(" ", $lenval) . '// ' . $eval['desc'];
            }
            return $r;
        };
        $varlentitle = mb_strlen('$' . $varname);
        if (in_array(gettype($var), array('object', 'array'))) {
            return '$' . $varname . str_repeat(" ", ($indents['key'] - $varlentitle)) . '=[' . str_repeat(" ", $indents['val'] - 1) . '// main array node' . rtrim($pretty($indents, $varlentitle, $var), ',') . ';';
        } else {
            $eval = $this->EvalVariable($var);
            return '$' . $varname . str_repeat(" ", $indents['key']) . '=' . $eval['val'] . ';' . str_repeat(" ", $indents['val'] - 1) . '// ' . $eval['desc'];
        }

    }

    public function EvalVariable($var): array
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
        if (strpos($string, 'resource') !== false) {
            return ['val' => 'resource', 'desc' => rtrim($string) . '.'];
        } elseif (strpos($string, 'of type ') !== false) {
            return ['val' => 'resource', 'desc' => rtrim($string) . '.'];
        }
        unset($string);

        if (strpos($var, ' ') !== false && strpos($var, ':') !== false && strpos($var, '-') !== false) {
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

        if ($this->ValidateDate($var) && strpos($var, ':') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value time.'];
        }

        if ($this->ValidateDate($var) && mb_strlen($var) >= 8 && strpos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value date.'];
        }

        if ($this->ValidateDate($var) && mb_strlen($var) >= 8 && strpos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') string value date.'];
        }

        if (is_string($var)) {
            $arr           = $this->strsplitunicode($var);
            $currencylist  = ['¤', '$', '¢', '£', '¥', '₣', '₤', '₧', '€', '₹', '₩', '₴', '₯', '₮', '₰', '₲', '₱', '₳', '₵', '₭', '₪', '₫', '₠', '₡', '₢', '₥', '₦', '₨', '₶', '₷', '₸', '₺', '₻', '₼', '₽', '₾', '₿'];
            $currencycheck = [];
            foreach ($arr as $char) {
                if (in_array($char, $currencylist, true)) {
                    $currencycheck[] = $char;
                }
            }
            if (!empty($currencycheck)) {
                return ['val' => "'" . $var . "'", 'desc' => 'string/amount value related to currency (' . implode(',', $currencycheck) . ').'];
            }
        }

        if (is_string($var)) {
            return ['val' => "'" . $var . "'", 'desc' => 'string value of ' . mb_strlen($var) . ' character.'];
        }

        return ['val' => 'unknow', 'desc' => 'unknow'];;
    }

    private function ValidateDate(string $date): bool
    {
        return ($timestamp = strtotime($date)) != false;
    }

    private function strsplitunicode(string $str, $length = 1): array
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

    private function HighlightCode(string $string): string
    {
        $bg = $this->background;
        return '<style>body{background-color: ' . ($bg == '' ? white : $bg) . '}</style>' . highlight_string("<?php \n#output of Variable:" . str_repeat(' ', 10) . '*****| Theme Used: ' . $this->themeused . " |*****\n" . $string . "\n?>", true);
    }

    private function OutView(string $string)
    {
        echo $string;
    }
}