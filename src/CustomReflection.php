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

use Reflection;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;

/**
 * Custom_Reflection Class.
 *
 */
class CustomReflection
{
    /**
     * Description: instantiate tool kit of Commons class.
     * @var Commons
     */
    private Commons $commons;

    /**
     * Description: instantiate tool kit of Analyzer class.
     * @var Analyzer
     */
    private Analyzer $analyzer;

    /**
     * Description: Constructor of the Class Custom_Reflection
     */
    public function __construct($instance_analyzer)
    {
        $this->commons  = new Commons();
        $this->analyzer = $instance_analyzer;
    }

    /**
     * Description: it recursively takes an object and iterates it in search of
     * properties, constants, methods and traits, It also evaluates the visibility
     * and scope attributes of each one, it also evaluates in the case of the
     * properties and constant to which description it belongs.
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    public function descriptionObject(object $object): array
    {
        $reflection = new ReflectionClass($object);
        $traits     = $this->getTraits($object);
        $result     = [
            'class'      => $reflection->getName(),
            'traits'     => (empty($traits) ? '' : implode(',', $traits)),
            'properties' => $this->getProperties($object),
            'constants'  => $this->getConstants($object),
            'methods'    => $this->getMethods($object),
            'comment'    => 'object node of Class: '
                . $reflection->getName()
                . (empty($traits) ? '' : ', implement of traits: ' . implode(',', $traits))
            ,
        ];
        return array_filter($result, fn($value) => !empty($value));
    }

    /**
     * Description: this method will get traits that exist on an object.
     * @param $classInstance
     * @return array|false|string[]
     */
    public function getTraits($classInstance)
    {
        $parent_classes = class_parents($classInstance);
        $traits         = class_uses($classInstance);
        foreach ($parent_classes as $parent_class) {
            $traits = array_merge($traits, class_uses($parent_class));
        }
        return $traits;
    }

    /**
     * Description: this method will get properties that exist on an object.
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function getProperties(object $object): array
    {
        $reflection_object = new ReflectionObject($object);
        $properties_list   = $reflection_object->getProperties();
        $property_array    = [];
        foreach ($properties_list as $property) {
            $prop_data                          = $this->analyzeProperty($property, $object);
            $property_array[$prop_data['name']] = $prop_data;
        }
        return $property_array;
    }

    /**
     * Description: this method extracts the characteristics of the passed property.
     * @param  object  $property
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function analyzeProperty(object $property, object $object): array
    {
        $property->setAccessible(true);
        $property_init = $property->isInitialized($object);
        $value         = $property_init ? $property->getValue($object) : 'uninitialized';
        $value         = $this->analyzer->variableAnalyzer($value);
        if (isset($value['value']) && in_array(gettype($value), ['array', 'object'])) {
            $value = $value['value'];
        }
        return [
            'name'       => $property->getName(),
            'value'      => $value,
            'type'       => $property->getType() !== null ? $property->getType()->getName() : ($property_init ? gettype($property->getValue($object)) : 'null'),
            'class'      => get_class($object),
            'scope'      => $property->isStatic() ? 'static' : 'instance',
            'visibility' => $property->isPrivate() ? 'private' : ($property->isProtected() ? 'protected' : 'public'),
            'comment'    => $property_init ? $this->commons->descriptionVariable($property->getValue($object), $property->getName())['comment'] : 'uninitialized',
        ];
    }

    /**
     * Description: this method will get constants that exist on an object.
     * @param  object  $object
     * @return array
     */
    private function getConstants(object $object): array
    {
        $reflection_class = new ReflectionClass($object);
        $const_list       = $reflection_class->getConstants();
        $class            = $reflection_class->getName();
        $constant_array   = [];
        foreach ($const_list as $key => $const) {
            $constant_array[$key] = $this->analyzeConstant($key, $class, $const);
        }
        return $constant_array;
    }

    /**
     * Description: this method extracts the characteristics of the passed constant.
     * @param  string  $name
     * @param  string  $class
     * @param $value
     * @return array
     * @throws ReflectionException
     */
    private function analyzeConstant(string $name, string $class, $value): array
    {
        return [
            'name'      => $name,
            'value'     => $this->analyzer->variableAnalyzer($value)['value'],
            'type'      => gettype($value),
            'class'     => $class,
            'modifiers' => implode(',', Reflection::getModifierNames((new ReflectionClassConstant($class, $name))->getModifiers())),
            'comment'   => $this->commons->descriptionVariable($value, $name)['comment'],
        ];
    }

    /**
     * Description: this method will get methods that exist on an object.
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function getMethods(object $object): array
    {
        $reflection_Class = new ReflectionClass($object);
        $method_list      = $reflection_Class->getMethods();
        $method_array     = [];
        foreach ($method_list as $method) {
            $method_data                        = $this->analyzeMethod($method, $object);
            $method_array[$method_data['name']] = $method_data;
        }
        return $method_array;
    }

    /**
     * Description: this method extracts the characteristics of the passed method.
     * @param  object  $method
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function analyzeMethod(object $method, object $object): array
    {
        $method->setAccessible(true);
        $params_list      = $method->getParameters();
        $full_list_detail = [];
        foreach ($params_list as $param) {
            $name               = $param->getName();
            $type               = $param->getType()->getName();
            $full_list_detail[] = '(' . $type . ') ' . '$' . $name;
        }
        $return_type = $method->getReturnType();
        return [
            'name'      => $method->getName(),
            'code'      => $this->getCode($object, $method->getName()),
            'class'     => get_class($object),
            'modifiers' => '(' . implode(',', Reflection::getModifierNames($method->getModifiers())) . ')',
            'params'    => (empty($full_list_detail) ? 'no parameters' : 'params(' . implode(',', $full_list_detail) . ')'),
            'return'    => ($return_type != null ? $return_type->getName() : 'undefined'),
        ];
    }

    /**
     * Description: this method extracts the code related to the method passed.
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