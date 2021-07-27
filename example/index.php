<?php

/**
 * This example shows how the BOHBasicOutputHandler class and its methods are declared.
 */


use IcarosNet\BOHBasicOutputHandler\Output as Output;

const PATH = __DIR__ . '\..\vendor\autoload.php';

if (file_exists(PATH)) {
    /** @noinspection PhpIncludeInspection */
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
    const CONST_ARRAY = ['a' => 1, 'b' => 2];
    const CONST_STRING = 'Constant String example';
    public static string $public_static_string_prop = 'Example Hello World! 2';
    protected static bool $protected_static_boolean_prop = true;
    private static object $private_static_object_prop;
    private static array $private_static_array_long_name = ['X' => 12, 'Y' => 24];
    public string $public_string_prop = 'Example Hello World!';
    public $file_read;
    protected int $pro_int_prop = 10;
    private array $private_array_long_name = ['a' => 1, 'b' => 2];

    public function __construct()
    {
        $file_name       = "file.txt";
        $this->file_read = fopen($file_name, "r");
    }

    public function fooMethod(): array
    {
        $c          = 0;
        $array_data = self::CONST_ARRAY;
        while ($this->pro_int_prop < $c) {
            $array_data[] = $c;
            ++$c;
        }
        return array_merge($this->private_array_long_name, $array_data);
    }

    protected function fooMethod2(): string
    {
        return $this->public_string_prop . ' ' . $this->fooMethod3();
    }

    public static function fooMethod3(): string
    {
        return self::$public_static_string_prop;
    }

    private final function fooMethod4()
    {

    }
}

/**
 * $variable_class is a variable storage of instance class FooBar.
 */
$variable_class = new FooBar;

/**
 * $example_single_string is a short variable to use as an example.
 */
$example_single_string = 'Hello World';

/**
 * $example_short_array is a short variable to use as an example.
 */
$example_short_array = ['a' => 1, 'b' => 2];

/**
 * $example_big_array is a large array variable to use as an example.
 */
$example_big_array = [
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
        'class'        => $variable_class,
        'resource'     => curl_init(),
    ],
    'array'        => [
        'boolean_true'       => true,
        'boolean_false'      => false,
        'boolean_true_text'  => 'true',
        'boolean_false_text' => 'false',
        'object'             => (object) [
            'key_index_most' => 'Hello World',
            'other'          => [
                'other' => 'other',
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
//echo '<h1>Hello World</h1>';
//$output->loadBOHDesign('full');


//$examplearray = ['int' => 120, 120.25, 'array' => ['int' => '120', 'float' => '120.25']];
//Example 1: Output the Basic Html in Line
$output->output($example_big_array);


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

