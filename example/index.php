<?php

/**
 * This example shows how the BOHBasicOutputHandler class and its methods are declared.
 */


use IcarosNet\BOHBasicOutputHandler\Output as Output;

const PATH = __DIR__ . '\..\vendor\autoload.php';

if (file_exists(PATH)) {
    require_once PATH;
} else {
    echo 'This library "[BOH] Basic Output Handler for PHP" requires composer installation and autoload; run composer install command in your root.';
    die;
}


/**
 * FooBar is an example class.
 */
class FooBar
{
    public string $public_string_prop = 'Example Hello World!';
    public static string $public_static_string_prop = 'Example Hello World! 2';
    protected int $pro_int_prop = 10;
    protected static bool $protected_static_boolean_prop = true;
    private array $priv_array_long_name = ['a' => 1, 'b' => 2];
    private static object $private_static_object_prop;
    private static array $priv_static_array_long_name = ['X' => 12, 'Y' => 24];

    public $file_read;

    const CONST_ARRAY = ['a' => 1, 'b' => 2];
    const CONST_STRING = 'Constante String';

    public function __construct()
    {
        $nombre_fichero  = "file.txt";
        $this->file_read = fopen($nombre_fichero, "r");
    }

    public function foofunction(): array
    {
        $c          = 0;
        $array_data = self::CONST_ARRAY;
        while ($this->pro_int_prop < $c) {
            $array_data[] = $c;
            ++$c;
        }
        return array_merge($this->priv_array_long_name, $array_data);
    }

    protected function foofunction2(): string
    {
        return $this->public_string_prop . ' ' . $this->foofunction3();
    }

    public static function foofunction3(): string
    {
        return self::$public_static_string_prop;
    }

    private final function foofunction4()
    {

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
    'objects_list' => [
        'object_empty' => (object) [],
        'class'        => $varclass,
        'resource'     => curl_init(),
    ],
    'array'        => [
        'boolean_true'       => true,
        'boolean_false'      => false,
        'boolean_true_text'  => 'true',
        'boolean_false_text' => 'false',
        'object'             => (object) [
            'key_index_most' => 'Hello Wolrd',
            'joder'          => [
                'prueba' => 'prueba',
            ],
        ],
        'nested'             => [
            'other_obj' => (object) [
                'apple',
                'banana',
                'coconut',
            ],
        ],
    ],

];

//Instance Class BOHBasicOutputHandler
$output = new Output();

/**
 * only to load and test the user interface with different Designs, do not
 * implement if your User Interface already implements a design framework.
 * Instead of this use setOptions()
 */
echo '<h1>Hello World</h1>';
//$output->loadBOHDesign('full');


//$examplearray = ['int' => 120, 120.25, 'array' => ['int' => '120', 'float' => '120.25']];
//Example 1: Output the Basic Html in Line
$output->output($examplearray);


echo '<pre>';

//$const = $output->getConsts($varclass);

echo '</pre>';
//Example 2: Output the rich html with theme color based
//$output->setOptions(['css' => 'BS4', 'theme' => 'monokai']);
//$output->output($examplearray);

//Example 3: Output json-string
//$output->outputJson($examplearray);

//Example 4: Output the rich html with theme color based
//$output->setOptions(['theme' => 'monokai']);
//$output->outputTerminal($examplearray);

/**
 * In line Instance:
 */

//Output::getInstance()->output($examplearray);

