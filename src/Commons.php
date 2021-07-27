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

class Commons
{
    public function getStringFromArray(array $data, array $indent): string
    {


        /*
        if (in_array(gettype($data), ['object', 'array'])) {
            $string = $this->fillCharBoth(
                    $title_text,
                    $indent['main'] + $indent['value'] + $indent['comments'] - $title_len,
                    '='
                ) . PHP_EOL .


                $this->repeatChar(" ", (($indent['main'] - $title_len) >= 0 ?
                    $indent['main'] - $title_len : 1)) . PHP_EOL .
                '= [' . $this->repeatChar(" ", $indent['value'] - 2);
        } else {
            //$eval   = $this->evaluateVariable($var);
            $string = '$variable' . $this->repeatChar(" ", $indent['main']) . '=' . ';' . $this->repeatChar(" ", $indent['value'] - 1) . '// ';
        }
        */
        return '';
    }

    /**
     * Filler of String.
     * @param  string  $text
     * @param  int  $repetitions
     * @param  string  $character
     * @return string
     */
    public function fillCharBoth(string $text, int $repetitions, string $character): string
    {
        return $repetitions > 0 ? $this->str_pad_unicode($text, $repetitions, $character, STR_PAD_BOTH) : $text;
    }

    private function str_pad_unicode($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT)
    {
        $str_len     = mb_strlen($str);
        $pad_str_len = mb_strlen($pad_str);
        if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
            $str_len = 1; // @debug
        }
        if (!$pad_len || !$pad_str_len || $pad_len <= $str_len) {
            return $str;
        }

        $result = null;
        if ($dir == STR_PAD_BOTH) {
            $length = ($pad_len - $str_len) / 2;
            $repeat = (int) ceil($length / $pad_str_len);
            $result = mb_substr(str_repeat($pad_str, $repeat), 0, (int) floor($length))
                . $str
                . mb_substr(str_repeat($pad_str, $repeat), 0, (int) ceil($length));
        } else {
            $repeat = ceil($str_len - $pad_str_len + $pad_len);
            if ($dir == STR_PAD_RIGHT) {
                $result = $str . str_repeat($pad_str, (int) $repeat);
                $result = mb_substr($result, 0, $pad_len);
            } else {
                if ($dir == STR_PAD_LEFT) {
                    $result = str_repeat($pad_str, (int) $repeat);
                    $result = mb_substr($result, 0,
                            $pad_len - (($str_len - $pad_str_len) + $pad_str_len))
                        . $str;
                }
            }
        }

        return $result;
    }

    /**
     * Filler of String.
     * @param  string  $text
     * @param  int  $repetitions
     * @param  string  $character
     * @return string
     */
    public function fillCharRight(string $text, int $repetitions, string $character): string
    {
        return $repetitions > 0 ? $this->str_pad_unicode($text, $repetitions, $character, STR_PAD_RIGHT) : $text;
    }

    /**
     * repeater of String.
     * @param  string  $character
     * @param  int  $repetitions
     * @return string
     */
    public function repeatChar(string $character, int $repetitions): string
    {
        return $repetitions > 0 ? str_repeat($character, $repetitions) : $character;
    }

    /**
     * repeater of String.
     * @param  string|int|null  $haystack
     * @param  string|int|null  $needle
     * @return false|int
     */
    public function checkStrPos($haystack, $needle)
    {
        return mb_strpos((string) $haystack, (string) $needle);
    }

    /**
     * captures a variable buffer and rhetoric as string
     * @param  mixed  $data
     * @return string
     */
    public function getBuffer($data): string
    {
        ob_start();
        var_dump($data);
        return ob_get_clean();
    }

    /**
     * repeater of String.
     * @param  array|string  $value
     * @param  string  $key
     * @param  array  $argument
     * @return void
     */
    public function getArrayData($value, string $key, array &$argument)
    {
        if (strcasecmp($key, $argument['search']) == 0 && $this->calculateLength($value) > $argument[0]) {
            $argument[0] = $this->calculateLength($value);
        }
    }

    /**
     * repeater of String.
     * @param $string
     * @return int
     */
    public function calculateLength($string): int
    {
        return mb_strlen((string) $string);
    }

    /**
     * repeater of String.
     * @param  array  $data
     * @param  string  $key
     * @return int
     */
    public function getHighestCharAmountByKey(array $data, string $key): int
    {
        $name_len = 0;
        array_walk_recursive($data, [$this, 'getArrayData'], ['search' => $key, &$name_len]);
        return $name_len;
    }
}