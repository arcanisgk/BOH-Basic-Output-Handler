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
    private static object $public_object_prop;
    private static $pub_st_string;

    public $public_string_prop = 'Example Hello World!';

    public static string $public_static_string = 'Public Static Property';
    protected int $pro_int = 10;
    protected static string $protected_string = 'Protected';

    private array $priv_array_long_name = ['a' => 1, 'b' => 2];
    private static array $priv_static_array_long_name = ['X' => 12, 'Y' => 24];

    const CONST_OBJECT = ['a' => 1, 'b' => 2];
    private $pub_string;

    public function foofunction(): array
    {
        $c          = 0;
        $array_data = self::CONST_OBJECT;
        while ($this->pro_int < $c) {
            $array_data[] = $c;
            ++$c;
        }
        return array_merge($this->priv_array_long_name, $array_data);
    }

    protected function foofunction2(): string
    {
        return $this->pub_string . ' ' . $this->foofunction3();
    }

    public static function foofunction3(): string
    {
        return self::$pub_st_string;
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

//$output->loadBOHDesign('full');

//Example 1: Output the Basic Html in Line
//$output->output($examplearray);

echo '<br>Hello World<br>';

echo '<pre>';
//secho var_dump($anal->name, $props, $consts);


$props = $output->getProps($varclass);
$const = $output->getConsts($varclass);
//faltan refleccion de metodos


echo var_dump($props, $const);


//echo var_dump($output->method($varclass)->isPrivate());
//echo var_dump($output->parameter($varclass)->getDefaultValue());
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

