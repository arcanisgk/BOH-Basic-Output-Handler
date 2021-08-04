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
     * @param $key
     * @return array
     */
    private function isNullType($value, $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'null', 'value' => '"null"', 'comment' => 'null value string.'] :
            ['name' => $key, 'type' => 'null', 'value' => 'null', 'comment' => 'null value.'];
    }

    /**
     * Description: This method should evaluate if is array type to add comment like node.
     * @param $value
     * @param $key
     * @return array
     */
    private function isArrayType($value, $key): array
    {
        return ['name' => $key, 'type' => 'array', 'value' => "", 'comment' => 'array node' . (empty($value) ? ' that is empty.' : '.')];
    }

    /**
     * Description: This method should evaluate if is boolean type like string.
     * @param $value
     * @param $key
     * @return array
     */
    private function isBoolType($value, $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'boolean', 'value' => '"' . $value . '"', 'comment' => 'string value boolean ' . $value . '.'] :
            ['name' => $key, 'type' => 'boolean', 'value' => ($value ? 'true' : 'false'), 'comment' => 'boolean value ' . ($value ? 'true' : 'false') . '.'];
    }

    /**
     * Description: This method should evaluate if is object.
     * @param $value
     * @param $key
     * @return array
     */
    private function isObjectType($value, $key): array
    {
        $string = explode('{', $this->getBuffer($value));
        return ['name' => $key, 'type' => 'object', 'value' => '(object) ', 'comment' => rtrim(reset($string)) . '.'];
    }

    /**
     * Description: This method should capture the buffer of a variable and returns it as a string
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
     * @param $key
     * @return array
     */
    private function isIntegerType($value, $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'integer', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') integer value string.'] :
            ['name' => $key, 'type' => 'integer', 'value' => $value, 'comment' => '(' . mb_strlen((string) $value) . ') integer value.'];
    }

    /**
     * Description: This method should evaluate if is float.
     * @param $value
     * @param $key
     * @return array
     */
    private function isFloatType($value, $key): array
    {
        return is_string($value) ?
            ['name' => $key, 'type' => 'float', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') float value string.'] :
            ['name' => $key, 'type' => 'float', 'value' => $value, 'comment' => '(' . mb_strlen((string) $value) . ') float value.'];
    }

    /**
     * Description: This method should evaluate if is resource.
     * @param $string
     * @param $key
     * @return array
     */
    private function isResourceType($string, $key): array
    {
        return ['name' => $key, 'type' => 'resource', 'value' => 'resource', 'comment' => rtrim($string) . '.'];
    }

    /**
     * Description: This method should validate Date String.
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
     * @param $key
     * @return array
     */
    private function isDateTimeType($value, $key): array
    {
        return ['name' => $key, 'string' => 'datetime', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value datetime.'];
    }

    /**
     * Description: This method should evaluate if is time.
     * @param $value
     * @param $key
     * @return array
     */
    private function isTimeType($value, $key): array
    {
        return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value time.'];
    }

    /**
     * Description: This method should evaluate if is date.
     * @param $value
     * @param $key
     * @return array
     */
    private function isDateType($value, $key): array
    {
        return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value date.'];
    }

    /**
     * Description: This should cut the strings in unicode format.
     * @param  string  $str
     * @param  int  $length  default 1
     * @return array
     */
    private function splitStrToUnicode(string $str, int $length = 1): array
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
     * Description: This method should evaluate if is string related to currency.
     * @param $value
     * @param $key
     * @param $currency_check
     * @return array
     */
    private function isStringCurrencyType($value, $key, $currency_check): array
    {
        return [
            'name'    => $key,
            'type'    => 'string',
            'value'   => '"' . $value . '"',
            'comment' => 'string/amount value related to currency (' . implode(',', $currency_check) . ').',
        ];
    }

    /**
     * Description: This method should evaluate if is string related to currency.
     * @param $value
     * @param $key
     * @return array
     */
    private function isStringType($value, $key): array
    {
        return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => 'string value of ' . mb_strlen($value) . ' character.'];
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
        array_walk_recursive($data, [$this, 'getArrayData'], ['search' => $key, &$name_len]);
        return $name_len;
    }

    /**
     * Description: This method is a callbacks for parsing the getHighestCharacterAmountByKey method
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
     * @param  array  $data
     * @return int
     */
    public function calculateDeepArray(array $data): int
    {
        $max_depth = 0;
        foreach ($data as $value) {
            if (is_array($value)) {
                $depth = $this->calculateDeepArray($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return ($max_depth - 1) < 0 ? 4 : ($max_depth - 1);
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
}