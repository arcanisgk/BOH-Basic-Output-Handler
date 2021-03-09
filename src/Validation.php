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


use Exception;

class Validation
{
    /**
     * capture the environment for usage in the class.
     * Options: empty (default), __construct update to 'ENVIRONMENT_OUTPUT_HANDLER'
     * constant or implementor defined environment.
     * 'cli','web'
     *
     * @var string
     */
    protected string $env = '';

    /**
     * List of CURENCY and Respective flags for Cli Themes.
     *
     * @var array
     */
    const CURRENCIESLIST = [
        '¤', '$', '¢', '£', '¥', '₣', '₤', '₧', '€', '₹', '₩', '₴', '₯', '₮',
        '₰', '₲', '₱', '₳', '₵', '₭', '₪', '₫', '₠', '₡', '₢', '₥', '₦', '₨',
        '₶', '₷', '₸', '₺', '₻', '₼', '₽', '₾', '₿'
    ];

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->env = ENVIRONMENT_OUTPUT_HANDLER;
    }

    /**
     * check if runtime environment is CLI
     *
     * @return bool
     */
    public static function IsCommandLineInterface(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * environment setter; if the implementer is wrong;
     * the library will abort any execution immediately
     *
     * @param  string  $env
     *
     */
    public function setEnvironment(string $env): void
    {
        $this->env = $env;
    }

    /**
     * environment checker; if the implementer is wrong;
     * the library will abort any execution immediately
     * and display an error message stating that it has happened.
     *
     * @param  null|string  $env
     *
     * @return string
     * @throws Exception
     */
    protected function validateEnvironment($env): string
    {
        try {
            $env = $env != '' ? $env ?? $this->env : $this->env;
            if ($this->IsCommandLineInterface() && $env == 'web') {
                throw new Exception('error: you are trying to run output() ' .
                    'method from CLI and it is not supported, ' .
                    'use OutputCli() or AdvanceOutput() with CLI argument  method instead.');
            } elseif (!$this->IsCommandLineInterface() && $env == 'cli') {
                throw new Exception('error: you are trying to run OutputCli() ' .
                    'method from web browser and it is not supported, ' .
                    'use Output() or AdvanceOutput() with HTML argument method instead.');
            } elseif ($env != 'web' && $env != 'cli') {
                throw new Exception('you are trying to run an environment (' . $env .
                    ') not related to this library, check the documentation.');
            }
            return $env;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            exit;
        }
    }

    /**
     * Evaluates the indentation that the values and
     * comments should have in the construction of the output
     *
     * @param  mixed  $var
     *
     * @return array
     */
    protected function getIndent($var): array
    {
        $data    = $var;
        $indents = ['key' => 0, 'val' => 0];
        if (is_array($data) || is_object($data)) {
            $data           = (array) $data;
            $data           = $this->convertObject2Array($data);
            $deep           = $this->calcDeepArray($data) * 4;
            $indents        = $this->calcIndent($indents, $data);
            $indents['key'] += $deep;
            $indents['val'] += $deep / 2;
        } else {
            $indents = ['key' => $this->calclen('variable'), 'val' => $this->calclen($data)];
        }
        return $indents;
    }

    /**
     * convert objets to array.
     *
     * @param  mixed
     *
     * @return mixed
     */
    private function convertObject2Array(&$value)
    {
        if (is_object($value) || is_array($value)) {
            $value = (array) $value;
            foreach ($value as $key => $child) {
                $value[$key] = $this->convertObject2Array($child);
            }
            return $value;
        } else {
            return $value;
        }
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
        $max_depth = 1;
        foreach ($array as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $depth = $this->calcDeepArray((array) $value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    /**
     * Calculate the number of characters in the key and in the value through a passed data..
     *
     * @param  array  $indents
     * @param  mixed  $data
     *
     * @return array
     */
    private function calcIndent(array &$indents, $data): array
    {
        if (is_array($data)) {
            foreach ($data as $key => $child) {
                $key = (string) $key;
                if ($this->checkStrPos($key, chr(0)) !== false) {
                    $key = str_replace(chr(0), "'::'", $key);
                    $key = substr($key, 4);
                }
                $indents['key'] = ($indents['key'] >= $this->calclen($key)) ? $indents['key'] : $this->calclen($key);
                $this->calcIndent($indents, $child);
            }
        } else {
            if (is_resource($data)) {
                $temp           = rtrim($this->getBuffer($data));
                $indents['val'] = ($indents['val'] >= $this->calclen($temp)) ? $indents['val'] : $this->calclen($temp);
            } else {
                $data           = (string) $data;
                $indents['val'] = ($indents['val'] >= $this->calclen($data)) ? $indents['val'] : $this->calclen($data);
            }
        }
        return $indents;
    }

    /**
     * This should do a pre-analysis of the passed variable
     * and reassemble the beginning and end of the parsed text
     *
     * @param  mixed  $var
     * @param  array  $indents
     *
     * @return string
     */
    protected function analyzeVariable($var, array $indents): string
    {


        $varlentitle = $this->calclen('$variable');
        $string      = '';


        /*
        if (in_array(gettype($var), ['object', 'array'])) {
            $string =
                '$variable' .
                $this->repeatChar(" ", (($indents['key'] - $varlentitle) >= 0 ? $indents['key'] - $varlentitle : 1)) .
                '= ['
                . $this->repeatChar(" ", $indents['val'] - 2) .
                '// main array node.'
                . rtrim($this->getVariableParsed($indents, $varlentitle, $var), ',') .
                ';';
        } else {
            $eval   = $this->evaluateVariable($var);
            $string = '$variable' . $this->repeatChar(" ", $indents['key']) . '=' . $eval['val'] . ';'
                . $this->repeatChar(" ", $indents['val'] - 1) . '// ' . $eval['desc'];
        }*/
        return $string;
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
    private function getVariableParsed($indents, $varlentitle, $v = '', $c = " ", $in = 0, $k = null)
    {
        $r = '';
        if (in_array(gettype($v), ['object', 'array'])) {
            $k = (string) $k;
            if ($this->checkStrPos($k, chr(0)) !== false) {
                $k = str_replace(chr(0), "'::'", $k);
                $k = substr($k, 4);
            }
            $lenname = $this->calclen("'$k'");
            $lenkeys = $indents['key'] - $in - $lenname;
            if ($lenkeys < 0) {
                $lenkeys = 0;
            }
            $eval   = $this->evaluateVariable($v);
            $v      = (array) $v;
            $lenkey = $indents['val'] - $this->calclen($eval['val']) + 1;
            if (empty($v)) {
                $r .= ($in != 0 ? $this->repeatChar($c, $in) : '') . (is_null($k) ? '' : "'$k'"
                        . $this->repeatChar($c, $lenkeys) . "=> " . $eval['val'] . "[],"
                        . $this->repeatChar(" ", $lenkey - 6) . "// "
                        . $eval['desc']) . (empty($v) ? '' : PHP_EOL);
            } else {
                $r .= ($in != 0 ? $this->repeatChar($c, $in) : '') . (is_null($k) ? '' : "'$k'"
                        . $this->repeatChar($c, $lenkeys) . "=> " . $eval['val'] . "["
                        . $this->repeatChar(" ", $lenkey - 4) . "// "
                        . $eval['desc']) . (empty($v) ? '' : PHP_EOL);
                foreach ($v as $sk => $vl) {
                    $r .= $this->getVariableParsed($indents, $varlentitle, $vl, $c, $in + 4, $sk) . PHP_EOL;
                }
                $r .= (empty($v) ? '],' : ($in != 0 ? $this->repeatChar($c, $in / 2) : '') .
                    (is_null($v) ? '' : $this->repeatChar($c, $in / 2) . "],"));
            }
        } else {
            if ($this->checkStrPos($k, chr(0)) !== false) {
                $k = str_replace(chr(0), "", $k);
            }
            $lenkey = $indents['key'] - $this->calclen("'$k'") - $in;
            if ($lenkey < 0) {
                $lenkey = 0;
            }
            $eval   = $this->evaluateVariable($v);
            $lenval = $indents['val'] - ($this->calclen("'" . $eval['val'] . "'"));
            if ($lenval < 0) {
                $lenval = 0;
            }
            $r .= ($in != -1 ? $this->repeatChar($c, $in) : '') . (is_null($k) ? '' : "'$k'"
                    . $this->repeatChar($c, $lenkey) . '=> ') . $eval['val']
                . $this->repeatChar(" ", $lenval) . '// ' . $eval['desc'];
        }
        return str_replace("\0", "", $r);
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
            return is_string($var) ? ['val' => "'null'", 'desc' => 'null value string.'] :
                ['val' => 'null', 'desc' => 'null value.'];
        }

        if (is_array($var)) {
            return ['val' => "", 'desc' => 'array node.'];
        }

        if (in_array($var, ["true", "false", true, false], true)) {
            return is_string($var) ? ['val' => "'" . $var . "'", 'desc' => 'string value boolean ' . $var . '.'] :
                ['val' => ($var ? 'true' : 'false'), 'desc' => 'boolean value ' . ($var ? 'true' : 'false') . '.'];

        }

        if (is_object($var)) {
            ob_start();
            var_dump($var);
            $string = explode('{', ob_get_clean());
            return ['val' => '(object) ', 'desc' => rtrim(reset($string)) . '.'];
        }

        if ((int) $var == $var && is_numeric($var)) {
            return is_string($var) ? ['val' => "'" . $var . "'", 'desc' => '(' . $this->calclen((string) $var) . ') integer value string.'] :
                ['val' => $var, 'desc' => '(' . $this->calclen((string) $var) . ') integer value.'];
        }

        if ((float) $var == $var && is_numeric($var)) {
            return is_string($var) ? ['val' => "'" . $var . "'", 'desc' => '(' . $this->calclen($var) . ') float value string.'] :
                ['val' => $var, 'desc' => '(' . $this->calclen($var) . ') float value.'];
        }

        ob_start();
        var_dump($var);
        $string = ob_get_clean();
        if ($this->checkStrPos($string, 'resource') !== false) {
            return ['val' => 'resource', 'desc' => rtrim($string) . '.'];
        } elseif ($this->checkStrPos($string, 'of type ') !== false) {
            return ['val' => 'resource', 'desc' => rtrim($string) . '.'];
        }
        unset($string);

        if ($this->checkStrPos($var, ' ') !== false && $this->checkStrPos($var, ':') !== false && $this->checkStrPos($var, '-') !== false) {
            $datetime = explode(" ", $var);
            $validate = 0;
            foreach ($datetime as $value) {
                if ($this->validateDate($value)) {
                    $validate++;
                }
            }
            if ($validate >= 2) {
                return ['val' => "'" . $var . "'", 'desc' => '(' . $this->calclen($var) . ') string value datetime.'];
            }
        }

        if ($this->validateDate($var) && $this->checkStrPos($var, ':') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . $this->calclen($var) . ') string value time.'];
        }

        if ($this->validateDate($var) && $this->calclen($var) >= 8 && $this->checkStrPos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . $this->calclen($var) . ') string value date.'];
        }

        if ($this->validateDate($var) && $this->calclen($var) >= 8 && $this->checkStrPos($var, '-') !== false) {
            return ['val' => "'" . $var . "'", 'desc' => '(' . $this->calclen($var) . ') string value date.'];
        }

        if (is_string($var)) {
            $arr           = $this->splitStrToUnicode($var);
            $currencycheck = [];
            foreach ($arr as $char) {
                if (in_array($char, self::CURRENCIESLIST, true)) {
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
            return ['val' => "'" . $var . "'", 'desc' => 'string value of ' . $this->calclen($var) . ' character.'];
        }

        return ['val' => 'unknown', 'desc' => 'unknown'];
    }

    /**
     * repeater of String.
     *
     * @param  $var
     *
     * @return int
     */
    private function calclen($var): int
    {
        return mb_strlen((string) $var);
    }

    /**
     * repeater of String.
     *
     * @param  string|int|null  $haystack
     * @param  string|int|null  $needle
     *
     * @return false|int
     */
    private function checkStrPos($haystack, $needle)
    {
        return mb_strpos((string) $haystack, (string) $needle);
    }

    /**
     * repeater of String.
     *
     * @param  string  $character
     * @param  int  $repetitions
     *
     * @return string
     */
    protected function repeatChar(string $character, int $repetitions): string
    {
        return $repetitions > 0 ? str_repeat($character, $repetitions) : $character;
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
     * captures a variable buffer and rhetoric as string
     *
     * @param  mixed  $data
     *
     * @return string
     */
    private function getBuffer($data): string
    {
        ob_start();
        var_dump($data);
        return ob_get_clean();
    }
}