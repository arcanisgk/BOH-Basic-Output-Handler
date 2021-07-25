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


use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class CustomReflectionObject
{
    /**
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    public function getProps(object $object): array
    {
        $props = (new ReflectionClass($object))->getProperties();
        foreach ($props as $key => $subobject) {
            $props[$key] = $this->analyzeProperty($subobject);
        }
        foreach ($props as $key => $object) {
            $props[$key] = (array) $object;
        }
        return $props;
    }

    /**
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function analyzeProperty(object $object): array
    {
        $prop               = new ReflectionProperty($object->class, $object->name);
        $name               = $prop->getName();
        $modifier           = $this->getPropertyModifiers($prop);
        $reflectionProperty = (new ReflectionClass($object->class))->getProperty($object->name);
        if ($prop->isPrivate() || $prop->isProtected()) {
            $reflectionProperty->setAccessible(true);
        }
        $value = $reflectionProperty->getValue(new $object->class);
        $type  = $prop->getType() !== null ? $prop->getType()->getName() : gettype($value);
        return ['name' => $name, 'scope' => $modifier, 'type' => $type, 'value' => $value];
    }

    /**
     * @param  object  $object
     * @return string
     */
    private function getPropertyModifiers(object $object): string
    {
        $mod = $object->getModifiers();
        return implode(' ', \Reflection::getModifierNames($mod));
    }

    public function getConsts($object): array
    {
        $reflectionClass = new ReflectionClass($object);
        $Consts          = $reflectionClass->getConstants();

        echo '<pre>';
        echo var_dump($Consts);
        echo '<pre>';

        $new_array = [];
        return $new_array;
    }


}