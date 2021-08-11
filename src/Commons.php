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
 * OutputHandler Class.
 *
 */
class Commons
{
    /**
     * List of currency.
     * @var array
     */
    const CURRENCIES_LIST = [
        '¤', '$', '¢', '£', '¥', '₣', '₤', '₧', '€', '₹', '₩', '₴', '₯',
        '₮', '₰', '₲', '₱', '₳', '₵', '₭', '₪', '₫', '₠', '₡', '₢', '₥',
        '₦', '₨', '₶', '₷', '₸', '₺', '₻', '₼', '₽', '₾', '₿',
    ];

    /**
     * Description: return validation if is empty.
     * @param $array
     * @return bool
     */
    public function isEmpty($array): bool
    {
        return empty($array);
    }

    /**
     * Description: parses the data type passed, evaluates and adds an enriched
     * description, and returns a descriptive array.
     * @param $value
     * @param  string  $key
     * @return array
     */
    public function descriptionVariable($value, string $key): array
    {

        if (null === $value || 'null' === $value || 'NULL' === $value) {
            return $this->isNullType($value, $key);
        }

        if (is_array($value)) {
            return $this->isArrayType($value, $key);
        }

        if (in_array($value, ["true", "false", true, false], true)) {
            return $this->isBoolType($value, $key);
        }

        if (is_object($value)) {
            return $this->isObjectType($value, $key);
        }

        if ((int) $value == $value && is_numeric($value)) {
            return $this->isIntegerType($value, $key);
        }

        if ((float) $value == $value && is_numeric($value)) {
            return $this->isFloatType($value, $key);

        }

        $string = $this->getBuffer($value);
        if (mb_strpos($string, 'resource') !== false || mb_strpos($string, 'of type ') !== false) {
            return $this->isResourceType($string, $key);
        }
        unset($string);

        if (mb_strpos($value, ' ') !== false && mb_strpos($value, ':') !== false && mb_strpos($value, '-') !== false) {
            $datetime = explode(" ", $value);
            $validate = 0;
            foreach ($datetime as $sub_value) {
                if ($this->validateDate($sub_value)) {
                    $validate++;
                }
            }
            if ($validate >= 2) {
                return $this->isDateTimeType($value, $key);

            }
        }

        if ($this->validateDate($value) && mb_strpos($value, ':') !== false) {
            return $this->isTimeType($value, $key);
        }

        if ($this->validateDate($value) && mb_strlen($value) >= 8 && mb_strpos($value, '-') !== false) {
            return $this->isDateType($value, $key);
        }

        if (is_string($value)) {
            $arr            = $this->splitStrToUnicode($value);
            $currency_check = [];
            foreach ($arr as $char) {
                if (in_array($char, self::CURRENCIES_LIST, true)) {
                    $currency_check[] = $char;
                }
            }
            if (!empty($currency_check)) {
                return $this->isStringCurrencyType($value, $key, $currency_check);
            }
        }

        if (is_string($value)) {
            return $this->isStringType($value, $key);
        }

        return $this->isUnknown();

    }

    /**
     * Description: This method should evaluate if the null type is a stored string.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isNullType($value, string $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'null', 'value' => '"null"', 'comment' => 'null value string'] :
            ['name' => $key, 'type' => 'null', 'value' => 'null', 'comment' => 'null value'];
    }

    /**
     * Description: This method should evaluate if is array type to add comment like node.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isArrayType($value, string $key): array
    {
        return ['name' => $key, 'type' => 'array', 'value' => "", 'comment' => 'array node' . (empty($value) ? ' that is empty' : '')];
    }

    /**
     * Description: This method should evaluate if is boolean type like string.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isBoolType($value, string $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'boolean', 'value' => '"' . $value . '"', 'comment' => 'string value boolean ' . $value . ''] :
            ['name' => $key, 'type' => 'boolean', 'value' => ($value ? 'true' : 'false'), 'comment' => 'boolean value ' . ($value ? 'true' : 'false') . ''];
    }

    /**
     * Description: This method should evaluate if is object.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isObjectType($value, string $key): array
    {
        $string = explode('{', $this->getBuffer($value));
        return ['name' => $key, 'type' => 'object', 'value' => '(object) ', 'comment' => rtrim(reset($string)) . ''];
    }

    /**
     * Description: This method should capture the output buffer with var_dump of a variable and returns it as a string
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
     * Description: This method should evaluate if is integer.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isIntegerType($value, string $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'integer', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') integer value string'] :
            ['name' => $key, 'type' => 'integer', 'value' => $value, 'comment' => '(' . mb_strlen((string) $value) . ') integer value'];
    }

    /**
     * Description: This method should evaluate if is float.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isFloatType($value, string $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'float', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') float value string'] :
            ['name' => $key, 'type' => 'float', 'value' => $value, 'comment' => '(' . mb_strlen((string) $value) . ') float value'];
    }

    /**
     * Description: This method should evaluate if is resource.
     * @param $string
     * @param  string  $key
     * @return array
     */
    private function isResourceType($string, string $key): array
    {
        return ['name' => $key, 'type' => 'resource', 'value' => 'resource', 'comment' => rtrim($string)];
    }

    /**
     * Description: This method should validate is string is DateTime String formatted.
     * @param  string  $date
     * @return bool
     */
    private function validateDate(string $date): bool
    {
        return (strtotime($date) !== false);
    }

    /**
     * Description: This method should evaluate if the datetime type is a stored string.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isDateTimeType($value, string $key): array
    {
        return ['name' => $key, 'type' => 'datetime', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value datetime'];
    }

    /**
     * Description: This method should evaluate if is time.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isTimeType($value, string $key): array
    {
        return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value time'];
    }

    /**
     * Description: This method should evaluate if is date.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isDateType($value, string $key): array
    {
        return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value date'];
    }

    /**
     * Description: This should cut the strings in unicode format.
     * @param  string  $str
     * @return array
     */
    private function splitStrToUnicode(string $str): array
    {
        $tmp    = preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY);
        $chunks = array_chunk($tmp, 1);
        foreach ($chunks as $i => $chunk) {
            $chunks[$i] = join('', (array) $chunk);
        }
        return $chunks;
    }

    /**
     * Description: This method should evaluate if is string related to currency.
     * @param $value
     * @param  string  $key
     * @param $currency_check
     * @return array
     */
    private function isStringCurrencyType($value, string $key, $currency_check): array
    {
        return [
            'name'    => $key,
            'type'    => 'string',
            'value'   => '"' . $value . '"',
            'comment' => 'string/amount value related to currency (' . implode(',', $currency_check) . ')',
        ];
    }

    /**
     * Description: This method should evaluate if is string related to currency.
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function isStringType($value, string $key): array
    {
        return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => 'string value of ' . mb_strlen($value) . ' character'];
    }

    /**
     * Description: This method should evaluate to unknown type data.
     * @return array
     */
    private function isUnknown(): array
    {
        return ['name' => 'unknown', 'type' => 'unknown', 'value' => 'unknown', 'comment' => 'unknown'];
    }

    /**
     * Description: This method should evaluate / iterate the passed array
     * and find the key with the most characters.
     * @param  array  $data
     * @param  string  $key
     * @return int
     */
    public function getHighestCharacterAmountByKey(array $data, string $key): int
    {
        $name_len = 0;
        array_walk_recursive($data, [$this, 'getArrayData'], ['search' => $key, 'count' => &$name_len]);
        return $name_len;
    }

    /**
     * Description: This method is a callbacks for parsing the @qmethod(getHighestCharacterAmountByKey).
     * @param  array|string  $value
     * @param  string  $key
     * @param  array  $argument
     * @return void
     */
    public function getArrayData($value, string $key, array &$argument)
    {
        if (strcasecmp($key, $argument['search']) == 0 && $this->calculateLength($value) > $argument['count']) {
            $argument['count'] = $this->calculateLength($value) + 2;
        }
    }

    /**
     * Description: This method calculates the size of a character string given.
     * @param $string
     * @return int
     */
    public function calculateLength($string): int
    {
        return mb_strlen((string) $string);
    }

    /**
     * Description: This method should evaluate / iterate the passed array
     * and find the highest deep node.
     * @param  mixed  $data
     * @return int
     */
    public function calculateDeepArray($data): int
    {
        $max_depth = 0;
        if (gettype($data) == 'array' || gettype($data) == 'object') {
            $data = gettype($data) == 'object' ? (array) $data : $data;
            foreach ($data as $value) {
                if (is_array($value)) {
                    $depth = $this->calculateDeepArray($value) + 1;
                    if ($depth > $max_depth) {
                        $max_depth = $depth;
                    }
                }
            }
        }
        //return ($max_depth - 1) < 0 ? 4 : ($max_depth - 1);
        return $max_depth;
    }

    /**
     * Description: This method should evaluate the passed number is pair.
     * @param $number
     * @return bool
     */
    public function isPair($number): bool
    {
        return ($number % 2 == 0);
    }

    /**
     * Filler of String to Both sides.
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
     * Filler of String Unicode.
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

    /**
     * space justified.
     * @param $string
     * @param $max
     * @return string
     */
    public function spaceJustify($string, $max): string
    {
        $max          = $max < 0 ? 0 : $max;
        $lines_reader = [];
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $string) as $line) {
            if ($this->calculateLength($line) > 1) {
                $lines_reader[] = $this->fillCharRight(rtrim($line, ' '), $max, ' ');
            }
        }
        return implode("\n", $lines_reader);
    }

    /**
     * Filler of String to Right.
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
     * Description: Take a line and Validate if it is the correct size; if it
     * detects that the line does not cost and adds the necessary line breaks
     * for its visualization.
     * @param  string  $line
     * @param  array  $indent
     * @return string
     */
    public function lineValidation(string $line, array $indent): string
    {
        $line   = rtrim($line);
        $buffer = [];
        if ($this->calculateLength($line) > $indent['total']) {
            $buffer = $this->getLineOptimized(
                $line,
                $indent['total']
            );
        } else {
            $buffer[] = $line;
        }
        return implode(PHP_EOL, $buffer);
    }

    /**
     * Description: This method would optimize the given line to work the line
     * break of the comments.
     * @param  string  $line
     * @param  int  $line_limit
     * @return array
     */
    public function getLineOptimized(string $line, int $line_limit): array
    {
        $structure      = explode('// ', $line);
        $new_indent     = $this->calculateLength($structure[0]);
        $comments_words = explode(' ', trim($structure[1]));
        $current_length = 0;
        $new_lines      = [];
        $index          = 0;
        foreach ($comments_words as $word) {
            if (!isset($new_lines[$index])) {
                $new_lines[$index] = $structure[0] . '// ' . $word;
            } else {
                if (($current_length <= $line_limit) && $this->calculateLength($new_lines[$index] . ' ' . $word) < $line_limit) {
                    $new_lines[$index] .= ' ' . $word;
                } else {
                    $index             += 1;
                    $new_lines[$index] = $this->repeatChar(' ', $new_indent) . '// ' . $word;
                }
            }
            $current_length = $this->calculateLength($new_lines[$index]);
        }
        return $new_lines;
    }
}