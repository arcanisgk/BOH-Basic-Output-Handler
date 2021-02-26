<?php

/**
 * This example shows how the BOHBasicOutputHandler class and its methods are declared.
 */

//Import the PHPMailer class into the global namespace
use \IcarosNet\BOHBasicOutputHandler as Output;

require __DIR__ . '\..\vendor\autoload.php';

/**
 * FooBar is an example class.
 */
class FooBar
{
    function foofunction()
    {
        return "Hello World!";
    }
}

/**
 * $varclass is a variable storage of instance class FooBar.
 */
$varclass = new FooBar;

/**
 * $examplesingle is a short variable to use as an example.
 */
$examplesingle = 'Hello World';

/**
 * $exampleshortarray is a short variable to use as an example.
 */
$exampleshortarray = ['a' => 1, 'b' => 2];

/**
 * $examplearray is a large array variable to use as an example.
 */
$examplearray = [
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
    'datetime_1'   => '2021-01-17 17:31:00',
    'datetime_2'   => '2021-Jan-17 6:31:00 AM',
    'datetime_3'   => '2021-01-17 6:31:00 AM',
    'datetime_4'   => '2021-Jan-17 17:31:00',
    'currency_1'   => '1.45$',
    'currency_2'   => 'db£ 1.45 ₹',
    'array'        => [
        'boolean_true'       => true,
        'boolean_false'      => false,
        'boolean_true_text'  => 'true',
        'boolean_false_text' => 'false',
        'object'             => (object) [
            'key_index_most' => 'Hello Wolrd',
            'joder'          => [
                'prueba' => 'prueba',
            ]
        ],
        'nested'             => [
            'other_obj' => (object) [
                'apple',
                'banana',
                'coconut',
            ],
        ],
    ],
    'objects_list' => [
        'object_empty' => (object) [],
        'class'        => $varclass,
        'resource'     => curl_init(),
    ],
];

//Instance Class BOHBasicOutputHandler
$output = new Output\OutputHandler();

//Theme Selection
$output->getTheme('monokai');

//example 1:
$output->output($examplearray);

$output->getTheme('natural-flow');

//example 2:
$output->output($examplearray);

//example 3:
$output->output($exampleshortarray);

