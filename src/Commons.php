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

    public function getStringFromArray($indent, $data, $in = 0, $k = null): string
    {

        //echo '<pre>';
        //echo var_dump($data);
        //echo '</pre>';

        $buffer = '';
        if (gettype($data) == 'array') {
            if (isset($data['type']) && $data['type'] != 'array') {
                $buffer .= $this->fillCharRight('', $in, ' ')
                    . $this->fillCharRight(
                        ($k === null ? '$unknown' : "'$k'"),
                        $indent['main'] - $in,
                        ' ')
                    . '=> ' . $this->fillCharRight(
                        $data['value'] . ',',
                        $indent['value'],
                        ' ')
                    . '// ' . $this->fillCharRight(
                        $data['comment'],
                        ($indent['total'] - $indent['comments']),
                        ' ')
                    . PHP_EOL;
            } elseif (isset($data[''])) {
                $buffer .= $this->fillCharRight(
                        ($k === null ? '$array' : "'$k'"),
                        ($indent['main']),
                        ' ')
                    . '=[ ' . $this->fillCharRight(
                        '',
                        $indent['value'],
                        ' ')
                    . '// ' . $this->fillCharRight(
                        $data['']['comment'],
                        ($indent['total'] - $indent['comments']),
                        ' ')
                    . PHP_EOL;
                $in     += 4;
                foreach ($data['']['value'] as $sk => $sub_data) {
                    $buffer .= $this->getStringFromArray(
                        $indent,
                        $sub_data,
                        $in,
                        $sk);
                }
                $buffer .= $this->fillCharRight(
                    '];',
                    $indent['total'],
                    ' ');
            } elseif (isset($data[$k]['type']) && $data[$k]['type'] == 'array') {
                $auto_close = isset($data[$k]['value']) && !empty($data[$k]['value']) ? '=[ ' : '=[] ';
                $buffer     .= $this->fillCharRight(
                        '',
                        $in,
                        ' ')
                    . $this->fillCharRight(
                        ($k === null ? '$array' : "'$k'"),
                        ($indent['main'] - $in),
                        ' ')
                    . $this->fillCharRight(
                        $auto_close,
                        $indent['value'] + 3,
                        ' ')
                    . '// ' . $this->fillCharRight(
                        $data[$k]['comment']
                        . ($auto_close == '=[] ' ? ' (empty array)' : ''),
                        ($indent['total'] - $indent['comments']),
                        ' ')
                    . PHP_EOL;
                $in         += 4;
                foreach ($data[$k]['value'] as $sk => $sub_data) {
                    $buffer .= $this->getStringFromArray(
                        $indent,
                        $sub_data,
                        $in,
                        $sk);
                }
                if ($auto_close != '=[] ') {
                    $buffer .= $this->fillCharRight(
                            '',
                            $in - 4,
                            ' ')
                        . $this->fillCharRight(
                            '],',
                            $indent['total'],
                            ' ')
                        . PHP_EOL;
                }
            } elseif (isset($data['class'])) {
                //echo '<pre>';
                //echo var_dump($data);
                //echo '</pre>';
                $auto_close = isset($data['properties']) || isset($data['constants']) || isset($data['methods']) ? '=(object)[ ' : '=(object)[], ';
                $buffer     .= $this->fillCharRight(
                        '',
                        $in,
                        ' ')
                    . $this->fillCharRight(
                        ($k === null ? "'object'" : "'$k'"),
                        ($indent['main'] - $in),
                        ' ')
                    . $this->fillCharRight(
                        $auto_close,
                        $indent['value'] + 3,
                        ' ')
                    . '// ' . $this->fillCharRight(
                        'object node of Class (' . $data['class'] . ')',
                        ($indent['total'] - $indent['comments']),
                        ' ')
                    . PHP_EOL;
                $in         += 4;
                // Property Analysis
                if (isset($data['properties'])) {
                    foreach ($data['properties'] as $sub_data) {
                        $is = '(property) ';
                        if (gettype($sub_data['value']) != 'array') {
                            $sub_data['value'] = gettype($sub_data['value']) == 'string' ? "'" . $sub_data['value'] . "'" : $sub_data['value'];
                            $line              = $this->fillCharRight(
                                    '',
                                    $in,
                                    ' ')
                                . $this->fillCharRight(
                                    $sub_data['name'] === null ? "'unknown'" : "'"
                                        . $sub_data['name']
                                        . "'", $indent['main'] - $in,
                                    ' ')
                                . '=> ' . $this->fillCharRight(
                                    $sub_data['value']
                                    . ',', $indent['value'],
                                    ' ')
                                . '// ' . $this->fillCharRight(
                                    $is . $sub_data['comment']
                                    . ' (scope: '
                                    . $sub_data['scope']
                                    . ', visibility: '
                                    . $sub_data['visibility']
                                    . ').',
                                    ($indent['total'] - $indent['comments'])
                                    , ' ');
                            $buffer            .= $this->lineValidation($line, $indent);
                        } elseif (isset($sub_data['value'][''])) {
                            $line   = $this->fillCharRight(
                                    '',
                                    $in,
                                    ' ')
                                . $this->fillCharRight(
                                    $sub_data['name'] === null ? "'unknown'" : "'" . $sub_data['name'] . "'",
                                    $indent['main'] - $in,
                                    ' ')
                                . '=[ ' . $this->fillCharRight(
                                    '',
                                    $indent['value'],
                                    ' ')
                                . '// ' . $this->fillCharRight(
                                    $is . $sub_data['comment']
                                    . ' (scope: ' . $sub_data['scope']
                                    . ', visibility: ' . $sub_data['visibility'] . ').',
                                    ($indent['total'] - $indent['comments']),
                                    ' ');
                            $buffer .= $this->lineValidation($line, $indent);
                            foreach ($sub_data['value'][''] as $inner_data) {
                                if (gettype($inner_data) == 'array') {
                                    foreach ($inner_data as $sks => $inner_sub_data) {
                                        $buffer .= $this->getStringFromArray(
                                            $indent,
                                            $inner_sub_data,
                                            $in + 4,
                                            $sks);
                                    }
                                }
                            }
                            $buffer .= $this->fillCharRight(
                                    '',
                                    $in,
                                    ' ')
                                . $this->fillCharRight(
                                    '],',
                                    $indent['total'],
                                    ' ')
                                . PHP_EOL;
                        }
                    }
                }
                // Property Constants
                if (isset($data['constants'])) {
                    foreach ($data['constants'] as $sub_data) {
                        $is = '(constant) ';
                        if (isset($sub_data['value']['value'])) {
                            $line   = $this->fillCharRight(
                                    '',
                                    $in,
                                    ' ')
                                . $this->fillCharRight(
                                    $sub_data['name'] === null ? "'unknown'" : "'"
                                        . $sub_data['name']
                                        . "'", $indent['main'] - $in,
                                    ' ')
                                . '=> ' . $this->fillCharRight(
                                    $sub_data['value']['value']
                                    . ',', $indent['value'],
                                    ' ')
                                . '// ' . $this->fillCharRight(
                                    $is . $sub_data['value']['comment']
                                    . ' (modifiers: ' . $sub_data['modifiers'] . ').',
                                    ($indent['total'] - $indent['comments'])
                                    , ' ');
                            $buffer .= $this->lineValidation($line, $indent);
                        } elseif (isset($sub_data['value'][''])) {
                            $line   = $this->fillCharRight(
                                    '',
                                    $in,
                                    ' ')
                                . $this->fillCharRight(
                                    $sub_data['name'] === null ? "'unknown'" : "'" . $sub_data['name'] . "'",
                                    $indent['main'] - $in,
                                    ' ')
                                . '=[ ' . $this->fillCharRight(
                                    '',
                                    $indent['value'],
                                    ' ')
                                . '// ' . $this->fillCharRight(
                                    $is . $sub_data['comment']
                                    . ' (modifiers: ' . $sub_data['modifiers'] . ').',
                                    ($indent['total'] - $indent['comments']),
                                    ' ');
                            $buffer .= $this->lineValidation($line, $indent);
                            foreach ($sub_data['value'][''] as $inner_data) {
                                if (gettype($inner_data) == 'array') {
                                    foreach ($inner_data as $sks => $inner_sub_data) {
                                        $buffer .= $this->getStringFromArray(
                                            $indent,
                                            $inner_sub_data,
                                            $in + 4,
                                            $sks);
                                    }
                                }
                            }
                            $buffer .= $this->fillCharRight(
                                    '',
                                    $in,
                                    ' ')
                                . $this->fillCharRight(
                                    '],',
                                    $indent['total'],
                                    ' ')
                                . PHP_EOL;
                        }
                    }
                }


                // Property Methods

                /*
                if (isset($data['properties'])) {
                    foreach ($data['properties'] as $sk => $sub_data) {
                        if (gettype($sub_data['value']) != 'array') {
                            $sub_data['value'] = gettype($sub_data['value']) == 'string' ? "'" . $sub_data['value'] . "'" : $sub_data['value'];
                            $line              = $this->fillCharRight(
                                    '',
                                    $in,
                                    ' ')
                                . $this->fillCharRight(
                                    $sub_data['name'] === null ? "'unknown'" : "'"
                                        . $sub_data['name']
                                        . "'", $indent['main'] - $in,
                                    ' ')
                                . '=> ' . $this->fillCharRight(
                                    $sub_data['value']
                                    . ',', $indent['value'],
                                    ' ')
                                . '// ' . $this->fillCharRight(
                                    $sub_data['comment']
                                    . ' (scope: '
                                    . $sub_data['scope']
                                    . ', visibility: '
                                    . $sub_data['visibility']
                                    . ').',
                                    ($indent['total'] - $indent['comments'])
                                    , ' ');
                            $buffer            .= $this->lineValidation($line, $indent, $in);
                        } elseif (isset($sub_data['value'][''])) {
                            $line   = $this->fillCharRight('', $in + 4, ' ')
                                . $this->fillCharRight($sub_data['name'] === null ? "'unknown'" : "'" . $sub_data['name'] . "'", $indent['main'] - $in - 4, ' ')
                                . '=[ ' . $this->fillCharRight('', $indent['value'], ' ')
                                . '// ' . $this->fillCharRight($sub_data['comment'] . ' (scope: ' . $sub_data['scope'] . ', visibility: ' . $sub_data['visibility'] . ').', ($indent['total'] - $indent['comments']), ' ');
                            $buffer .= $this->lineValidation($line, $indent, $in - 5);
                            foreach ($sub_data['value'][''] as $inner_data) {
                                if (gettype($inner_data) == 'array') {
                                    foreach ($inner_data as $sks => $inner_sub_data) {
                                        $buffer .= $this->getStringFromArray($indent, $inner_sub_data, $in + 8, $sks);
                                    }
                                }
                            }
                            $buffer .= $this->fillCharRight('', $in + 4, ' ') . $this->fillCharRight('],', $indent['total'], ' ') . PHP_EOL;
                        }
                    }
                }
                */

                if ($auto_close != '=(object)[], ') {
                    $buffer .= $this->fillCharRight('', $in - 4, ' ') . $this->fillCharRight('],', $indent['total'], ' ') . PHP_EOL;
                }
            }
        }
        return $buffer;
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
        return $repetitions > 0 ? $this->str_pad_unicode($text, $repetitions, $character) : $text;
    }

    /**
     * Filler of String.
     * @param $str
     * @param $pad_len
     * @param  string  $pad_str
     * @param  int  $dir
     * @return string
     */
    private function str_pad_unicode($str, $pad_len, string $pad_str = ' ', int $dir = STR_PAD_RIGHT): string
    {
        $str_len     = $this->calculateLength($str);
        $pad_str_len = $this->calculateLength($pad_str);
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
            $result = mb_substr($this->repeatChar($pad_str, $repeat), 0, (int) floor($length))
                . $str
                . mb_substr($this->repeatChar($pad_str, $repeat), 0, (int) ceil($length));
        } else {
            $repeat = ceil($str_len - $pad_str_len + $pad_len);
            if ($dir == STR_PAD_RIGHT) {
                $result = $str . $this->repeatChar($pad_str, (int) $repeat);
                $result = mb_substr($result, 0, $pad_len);
            } else {
                if ($dir == STR_PAD_LEFT) {
                    $result = $this->repeatChar($pad_str, (int) $repeat);
                    $result = mb_substr($result, 0, $pad_len - (($str_len - $pad_str_len) + $pad_str_len))
                        . $str;
                }
            }
        }
        return $result;
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

    public function lineValidation($line, $indent): string
    {
        $line   = rtrim($line);
        $buffer = '';
        if ($this->calculateLength($line) > $indent['total']) {
            $optimized_lines = $this->getLineOptimized(
                $line,
                $indent['total']);
            foreach ($optimized_lines as $number_line => $line) {
                if ($number_line == 0) {
                    $buffer .= $this->fillCharRight(
                            $line,
                            $indent['total'],
                            ' ')
                        . PHP_EOL;
                } else {
                    $buffer .= $this->fillCharRight(
                            '',
                            $indent['main'] + $indent['value'] + 3,
                            ' ')
                        . $this->fillCharRight(
                            '// ' . $line,
                            $indent['total'],
                            ' ')
                        . PHP_EOL;
                }
            }
        } else {
            $buffer .= $line . PHP_EOL;
        }
        return $buffer;
    }

    /**
     * repeater of String.
     * @param $line
     * @param $line_limit
     * @return array
     */
    public function getLineOptimized($line, $line_limit): array
    {
        $words          = explode(' ', $line);
        $current_length = 0;
        $index          = 0;
        $output         = [];
        foreach ($words as $word) {
            $word_length = $this->calculateLength($word) + 1;
            if (!isset($output[$index])) {
                $output = [$index => ''];
            }
            if (($current_length + $word_length) <= $line_limit) {
                $output[$index] .= $word . ' ';
                $current_length += $word_length;
            } else {
                $index          += 1;
                $current_length = $word_length;
                $output[$index] = $word . ' ';
            }
        }
        return $output;
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

    /**
     * Filler of String.
     * @param $number
     * @return bool
     */
    public function checkNumber($number): bool
    {
        return ($number % 2 == 0);
    }

    public function cleanLinesString($string, $max): string
    {
        $linesReader = '';
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $string) as $line) {
            $line        = rtrim($line);
            $linesReader .= $this->fillCharRight($line, $max, ' ') . PHP_EOL;
        }
        return $linesReader;
    }

    public function removePHPStart(string $text): string
    {
        return preg_replace('~(.*)<span>.*?</span>~', '$1', $text);
    }

}
