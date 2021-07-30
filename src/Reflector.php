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

use Reflection;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;

class Reflector
{
    /**
     * List of CURENCY and Respective flags for Cli Themes.
     *
     * @var array
     */
    const CURRENCIESLIST = [
        '¤', '$', '¢', '£', '¥', '₣', '₤', '₧', '€', '₹', '₩', '₴', '₯',
        '₮', '₰', '₲', '₱', '₳', '₵', '₭', '₪', '₫', '₠', '₡', '₢', '₥',
        '₦', '₨', '₶', '₷', '₸', '₺', '₻', '₼', '₽', '₾', '₿',
    ];

    /**
     *
     * @param $data
     * @return array
     * @throws ReflectionException
     */
    public function initReflectVariable($data): array
    {
        return ['type' => gettype($data), 'analyzed' => $this->getReflection($data)];
    }

    /**
     *
     * @param  $value
     * @param  string|null|int  $key
     * @param  array|null|int  $reference_array
     * @return array
     * @throws ReflectionException
     */
    private function getReflection($value, $key = null, &$reference_array = []): array
    {
        $type = gettype($value);
        if ($type == 'array') {
            $evaluation = $this->evaluateVariable($value, (string) $key);
            $reflection = ['value' => []];
            foreach ($value as $sk => $internal_value) {
                $reflection['value'][$sk] = $this->getReflection($internal_value, $sk, $reflection['value'][$sk]);
            }
            $reference_array[$key] = array_merge($evaluation, $reflection);
        } elseif ($type == 'object') {
            return $this->ReflectObject($value);
        } else {
            return $this->evaluateVariable($value, (string) $key);
        }
        return $reference_array;
    }

    /**
     * This should analyze each variable passed indicate the value and description of it.
     * note: the description is a rich text.
     *
     * @param $value
     * @param  string  $key
     * @return array
     */
    private function evaluateVariable($value, string $key = 'unknown'): array
    {
        if (null === $value || 'null' === $value || 'NULL' === $value) {
            return is_string($value) ?
                ['name' => $key, 'type' => 'null', 'value' => '"null"', 'comment' => 'null value string.'] :
                ['name' => $key, 'type' => 'null', 'value' => 'null', 'comment' => 'null value.'];
        }

        if (is_array($value)) {
            return ['name' => $key, 'type' => 'array', 'value' => "", 'comment' => 'array node.'];
        }

        if (in_array($value, ["true", "false", true, false], true)) {
            return is_string($value) ?
                ['name' => $key, 'type' => 'boolean', 'value' => '"' . $value . '"', 'comment' => 'string value boolean ' . $value . '.'] :
                ['name' => $key, 'type' => 'boolean', 'value' => ($value ? 'true' : 'false'), 'comment' => 'boolean value ' . ($value ? 'true' : 'false') . '.'];
        }

        if (is_object($value)) {
            ob_start();
            var_dump($value);
            $string = explode('{', ob_get_clean());
            return ['name' => $key, 'type' => 'object', 'value' => '(object) ', 'comment' => rtrim(reset($string)) . '.'];
        }

        if ((int) $value == $value && is_numeric($value)) {
            return is_string($value) ?
                ['name' => $key, 'type' => 'integer', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') integer value string.'] :
                ['name' => $key, 'type' => 'integer', 'value' => $value, 'comment' => '(' . mb_strlen((string) $value) . ') integer value.'];
        }

        if ((float) $value == $value && is_numeric($value)) {
            return is_string($value) ?
                ['name' => $key, 'type' => 'float', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') float value string.'] :
                ['name' => $key, 'type' => 'float', 'value' => $value, 'comment' => '(' . mb_strlen((string) $value) . ') float value.'];
        }

        ob_start();
        var_dump($value);
        $string = ob_get_clean();
        if (mb_strpos($string, 'resource') !== false) {
            return ['name' => $key, 'type' => 'resource', 'value' => 'resource', 'comment' => rtrim($string) . '.'];
        } elseif (mb_strpos($string, 'of type ') !== false) {
            return ['name' => $key, 'type' => 'resource', 'value' => 'resource', 'comment' => rtrim($string) . '.'];
        }
        unset($string);

        if (mb_strpos($value, ' ') !== false && mb_strpos($value, ':') !== false && mb_strpos($value, '-') !== false) {
            $datetime = explode(" ", $value);
            $validate = 0;
            foreach ($datetime as $subvalue) {
                if ($this->validateDate($subvalue)) {
                    $validate++;
                }
            }
            if ($validate >= 2) {
                return ['name' => $key, 'string' => 'datetime', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value datetime.'];
            }
        }

        if ($this->validateDate($value) && mb_strpos($value, ':') !== false) {
            return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value time.'];
        }

        if ($this->validateDate($value) && mb_strlen($value) >= 8 && mb_strpos($value, '-') !== false) {
            return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value date.'];
        }

        if ($this->validateDate($value) && mb_strlen($value) >= 8 && mb_strpos($value, '-') !== false) {
            return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => '(' . mb_strlen($value) . ') string value date.'];
        }

        if (is_string($value)) {
            $arr           = $this->splitStrToUnicode($value);
            $currencycheck = [];
            foreach ($arr as $char) {
                if (in_array($char, self::CURRENCIESLIST, true)) {
                    $currencycheck[] = $char;
                }
            }
            if (!empty($currencycheck)) {
                return [
                    'name'    => $key,
                    'type'    => 'string',
                    'value'   => '"' . $value . '"',
                    'comment' => 'string/amount value related to currency (' . implode(',', $currencycheck) . ').',
                ];
            }
        }

        if (is_string($value)) {
            return ['name' => $key, 'type' => 'string', 'value' => '"' . $value . '"', 'comment' => 'string value of ' . mb_strlen($value) . ' character.'];
        }

        return ['name' => 'unknown', 'type' => 'unknown', 'value' => 'unknown', 'comment' => 'unknown'];
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
     *
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    public function ReflectObject(object $object): array
    {
        $reflection = new ReflectionClass($object);
        $traits     = $this->getTraits($object);
        $result     = [
            'class'      => $reflection->getName(),
            'traits'     => (empty($traits) ? '' : implode(',', $traits)),
            'properties' => $this->getProps($object),
            'constants'  => $this->getConsts($object),
            'methods'    => $this->getMethods($object),
        ];
        return array_filter($result, fn($value) => !empty($value));
    }

    public function getTraits($classInstance)
    {
        $parentClasses = class_parents($classInstance);
        $traits        = class_uses($classInstance);
        foreach ($parentClasses as $parentClass) {
            $traits = array_merge($traits, class_uses($parentClass));
        }
        return $traits;
    }

    /**
     * Get Reflector from every Property of Object given.
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function getProps(object $object): array
    {
        $reflectionObject = new ReflectionObject($object);
        $proplist         = $reflectionObject->getProperties();
        $proparray        = [];
        foreach ($proplist as $prop) {
            $propdata                     = $this->analyzeProperty($prop, $object);
            $proparray[$propdata['name']] = $propdata;
        }
        return $proparray;
    }

    /**
     * @param  object  $prop
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function analyzeProperty(object $prop, object $object): array
    {
        $prop->setAccessible(true);
        $propinit = $prop->isInitialized($object);
        $value    = $propinit ? $prop->getValue($object) : 'uninitialized';
        $type     = gettype($value);
        if ($type === 'array' || $type === 'object') {
            $value = $this->getReflection($value);
        }
        return [
            'name'       => $prop->getName(),
            'value'      => $value,
            'type'       => $prop->getType() !== null ? $prop->getType()->getName() : ($propinit ? gettype($prop->getValue($object)) : 'null'),
            'class'      => get_class($object),
            'scope'      => $prop->isStatic() ? 'static' : 'instance',
            'visibility' => $prop->isPrivate() ? 'private' : ($prop->isProtected() ? 'protected' : 'public'),
            'comment'    => $propinit ? $this->evaluateVariable($prop->getValue($object), $prop->getName())['comment'] : 'uninitialized',
        ];
    }

    /**
     * Get Reflector from every Constant of Object given.
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function getConsts(object $object): array
    {
        $reflectionClass = new ReflectionClass($object);
        $constlist       = $reflectionClass->getConstants();
        $class           = $reflectionClass->getName();
        $constarray      = [];
        foreach ($constlist as $key => $const) {
            $constarray[$key] = $this->analyzeConstant($key, $class, $const, $reflectionClass);
        }
        return $constarray;
    }

    /**
     * @param  string  $name
     * @param  string  $class
     * @param $value
     * @param  object  $reflectionClass
     * @return array
     * @throws ReflectionException
     */
    private function analyzeConstant(string $name, string $class, $value, object $reflectionClass): array
    {
        return [
            'name'      => $name,
            'value'     => $this->getReflection($value),
            'type'      => gettype($value),
            'class'     => $class,
            'modifiers' => implode(',', Reflection::getModifierNames((new ReflectionClassConstant($class, $name))->getModifiers())),
            'comment'   => $this->evaluateVariable($value)['comment'],
        ];
    }

    /**
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function getMethods(object $object): array
    {
        $reflectionClass = new ReflectionClass($object);
        $methodlist      = $reflectionClass->getMethods();
        $methodarray     = [];
        foreach ($methodlist as $method) {
            $methoddata                       = $this->analyzeMethod($method, $object);
            $methodarray[$methoddata['name']] = $methoddata;
        }
        return $methodarray;
    }

    /**
     * @param  object  $method
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function analyzeMethod(object $method, object $object): array
    {
        $method->setAccessible(true);
        $paramsList       = $method->getParameters();
        $full_list_detail = [];
        foreach ($paramsList as $param) {
            $name               = $param->getName();
            $type               = $param->getType()->getName();
            $full_list_detail[] = '(' . $type . ') ' . '$' . $name;
        }
        return [
            'name'      => $method->getName(),
            'code'      => $this->getCode($object, $method->getName()),
            'class'     => get_class($object),
            'modifiers' => '(' . implode(',', Reflection::getModifierNames($method->getModifiers())) . ')',
            'params'    => (empty($full_list_detail) ? 'no parameters' : 'params(' . implode(',', $full_list_detail) . ')'),
        ];
    }

    /**
     * Get Reflector from every Constant of Object given.
     * @param  object  $obj
     * @param  string  $method
     * @return string
     * @throws ReflectionException
     */
    private function getCode(object $obj, string $method): string
    {
        $method = new ReflectionMethod($obj, $method);
        $file   = $method->getFileName();
        $source = file($file);
        $start  = $method->getStartLine() - 1;
        $end    = $method->getEndLine() - 1;
        return implode('', array_slice($source, $start, $end - $start + 1));
    }

}

