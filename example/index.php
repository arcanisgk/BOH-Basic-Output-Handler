<?php

use \IcarosNet\BOHBasicOutputHandler as Output;

require __DIR__ . '\..\vendor\autoload.php';

/**
 * FooBar is an example class.
 */
class FooBar
{
    function foo_function()
    {
        return "Hello World!";
    }
}

$var_class = new FooBar;

$example_single = true;

$example_single = '2021-Jan-17 6:31:00 AM';

$example_array = [//1
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
    'currency_2'   => '£ 1.45 ₹',
    'array'        => [//2
        'boolean_true'       => true,
        'boolean_false'      => false,
        'boolean_true_text'  => 'true',
        'boolean_false_text' => 'false',
        'object'             => (object) [//3
            'key_index_most_highed_of_the_example' => 'Hello Wolrd,Hello Wolrd,Hello Wolrd,Hello Wolrd',
            'joder'                                => [//4
                'prueba' => 'prueba',
            ]
        ],
        'nested'             => [                                               // deep = 3 no cuenta ya existe
            'other_obj' => (object) [                               // deep = 4 no cuenta ya existe
                'apple',
                'banana',
                'coconut',
            ],
        ],
    ],
    'objects_list' => [
        'object_empty' => (object) [],
        'class'        => $var_class,
        'resource'     => curl_init(),
    ],
];

$output = new Output\OutputHandler();
$output->output('example_array');
