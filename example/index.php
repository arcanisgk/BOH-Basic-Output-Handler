<?php

use \IcarosNet\BOHBasicOutputHandler as Output;

require __DIR__ . '\..\vendor\autoload.php';

#Generating Example for output
class Example
{
    function foo_function()
    {
        return "Hello World!";
    }
}

$var_class     = new Example;
$example_array = [
    'null'         => null,
    'null_text'    => 'null',
    'integer'      => 10,
    'integer_text' => '10',
    'float'        => 20.35,
    'float_text'   => '20.35',
    'string'       => 'Hello World',
    'date_1'       => '2021-01-17',
    'date_2'       => '2021-Jan-17',
    'hour_1'       => '6:31:00 AM',
    'hour_2'       => '17:31:00',
    'currency_1'   => '1.45$',
    'currency_2'   => '$ 1.45',
    'array'        => [
        'boolean_true'       => true,
        'boolean_false'      => false,
        'boolean_true_text'  => 'true',
        'boolean_false_text' => 'false',
    ],
    'objects'      => [
        'object'   => (object) '',
        'class'    => $var_class,
        'function' => $var_class->foo_function(),
    ],
];
$example_array = (object) $example_array;
$output        = new Output\Output_Handler();
$output->output('example_array');