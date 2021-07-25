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
use ReflectionException;
use ReflectionObject;
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
        $reflectionObject = new ReflectionObject($object);
        $proplist         = $reflectionObject->getProperties();
        foreach ($proplist as $key => $prop) {
            $proplist[$key] = $this->analyzeProperty($prop, $object);
        }
        dd($proplist);
        die;
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
        return [
            'name'       => $prop->getName(),
            'value'      => $propinit ? $prop->getValue($object) : 'uninitialized',
            'type'       => $prop->getType() !== null ? $prop->getType()->getName() : ($propinit ? gettype($prop->getValue($object)) : 'null'),
            'class'      => get_class($object),
            'scope'      => $prop->isStatic() ? 'static' : 'instance',
            'visibility' => $prop->isPrivate() ? 'private' : ($prop->isProtected() ? 'protected' : 'public'),
        ];
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

function dd(...$args)
{
    echo '<pre>';
    echo var_dump($args);
    echo '</pre>';
    die;
}