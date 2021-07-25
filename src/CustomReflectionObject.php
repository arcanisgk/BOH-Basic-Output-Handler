<?php


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
        $type               = $prop->getType()->getName();
        $modifier           = ['scope' => 'public', 'expose' => false];
        $modifier           = $this->getPropertyScope($prop);
        $reflectionProperty = (new ReflectionClass($object->class))->getProperty($object->name);
        if ($modifier['expose'] == true) {
            $reflectionProperty->setAccessible(true);
        }
        $value = $reflectionProperty->getValue(new $object->class);
        return ['name' => $name, 'scope' => $modifier['scope'], 'type' => $type, 'value' => $value];
    }

    /**
     * @param  object  $object
     * @return array
     * @throws ReflectionException
     */
    private function getPropertyScope(object $object): array
    {
        $scope  = 'public';
        $expose = false;
        if ($object->isProtected()) {
            $scope  = 'protected';
            $expose = true;
        } elseif ($object->isPrivate()) {
            $scope  = 'private';
            $expose = true;
        }
        if ($object->isStatic()) {
            $scope .= '-static';
        }
        return ['scope' => $scope, 'expose' => $expose];
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