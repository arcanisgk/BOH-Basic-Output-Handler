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


class Validation extends Commons
{
    /**
     * List of CURENCY and Respective flags for Cli Themes.
     *
     * @var array
     */
    const CURRENCIESLIST = [
        '¤', '$', '¢', '£', '¥', '₣', '₤', '₧', '€', '₹', '₩', '₴', '₯', '₮',
        '₰', '₲', '₱', '₳', '₵', '₭', '₪', '₫', '₠', '₡', '₢', '₥', '₦', '₨',
        '₶', '₷', '₸', '₺', '₻', '₼', '₽', '₾', '₿',
    ];

    /**
     * This should parse each variable passed and build the output string,
     * similar to var_dump or var_export.
     *
     * @param  mixed  $var
     * @param  array  $indents
     *
     * @return string
     */
    protected function getVariableToText($data, array $indents): string
    {
        $varname  = 'variable';
        $lentitle = mb_strlen('$' . $varname);

        if (in_array(gettype($data), ['object', 'array'])) {
            $string = '$variable' . $this->repeatChar(" ", (($indents['key'] - $lentitle) >= 0 ? $indents['key'] - $lentitle : 1)) . '= [' . $this->repeatChar(" ", $indents['val'] - 2) . '// main array node.' . PHP_EOL . rtrim($this->getParsed($indents, $lentitle, $data), ',') . ';';
        } else {
            $eval   = $this->evaluateVariable($data);
            $string = '$variable' . $this->repeatChar(" ", $indents['key']) . '=' . $eval['val'] . ';' . $this->repeatChar(" ", $indents['val'] - 1) . '// ' . $eval['desc'];
        }
        return $string;
    }

    protected function getParsed($indents, $lentitle, $v = '', $c = " ", $in = 0, $k = null): string
    {
        $r = '';
        if (in_array(gettype($v), ['object', 'array'])) {


            $k = (string) $k;

            /*
            if ($this->checkStrPos($k, chr(0)) !== false) {
                $k = str_replace(chr(0), "'::'", $k);
                $k = substr($k, 4);
            }*/

            $lenname = $this->calclen("'$k'");
            $lenkeys = $indents['key'] - $in - $lenname;

            if ($lenkeys < 0) {
                $lenkeys = 0;
            }


            $eval = $this->evaluateVariable($v);

            if (is_object($v)) {
                foreach ($v as $sk => $vl) {

                    echo '<pre>';
                    echo var_dump($sk, gettype($v->$sk));
                    echo '</pre>';
                }
            }


            $v = (array) $v;


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
                    //echo '<pre>';
                    //echo var_dump($sk, gettype($v[$sk]), $v[$sk]);
                    //echo '</pre>';
                    $r .= $this->getParsed($indents, $lentitle, $vl, $c, $in + 4, $sk) . PHP_EOL;
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
        return $r; //str_replace("\0", "", $r);
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
            return is_string($var) ? ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') integer value string.'] :
                ['val' => $var, 'desc' => '(' . mb_strlen((string) $var) . ') integer value.'];
        }

        if ((float) $var == $var && is_numeric($var)) {
            return is_string($var) ? ['val' => "'" . $var . "'", 'desc' => '(' . mb_strlen($var) . ') float value string.'] :
                ['val' => $var, 'desc' => '(' . mb_strlen((string) $var) . ') float value.'];
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
            $currencycheck = [];
            foreach ($arr as $char) {
                if (in_array($char, self::CURRENCIESLIST, true)) {
                    $currencycheck[] = $char;
                }
            }
            if (!empty($currencycheck)) {
                return [
                    'val' => "'" . $var . "'", 'desc' => 'string/amount value related to currency ('
                        . implode(',', $currencycheck) . ').',
                ];
            }
        }

        if (is_string($var)) {
            return ['val' => "'" . $var . "'", 'desc' => 'string value of ' . mb_strlen($var) . ' character.'];
        }

        return ['val' => 'unknown', 'desc' => 'unknown'];
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

}