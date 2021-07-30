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
    public const CONST_ARRAY = ['a' => 1, 'b' => 2];
    protected const CONST_STRING = 'Constant String example';
    private const CONST_INT = 5000;
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
 * $object is a short variable to use as an example stdClass.
 */
$object                 = new stdClass();
$object->int_example    = 100;
$object->string_example = 'Hello World 1';
$object->float_example  = 3.1416;
$object->array_example  = ['Hello World 2', 'test' => [10.35, '500']];

/**
 * $object_casted is a short variable to use as an example stdClass casted.
 */

$object_casted = (object) ['int1' => 120, 120.25, 'datos' => ['int2' => '120', 'float' => '120.25']];

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
    'array_empty'  => [],
    'objects_list' => [
        'object_empty'   => (object) [],
        'object_example' => $object,
        'object_casted'  => $object_casted,
        'class_stored'   => $variable_class,
        'resource'       => curl_init(),
    ],
    'array_node'   => [
        'boolean_true'       => true,
        'boolean_false'      => false,
        'boolean_true_text'  => 'true',
        'boolean_false_text' => 'false',
        'object_node'        => (object) [
            'key_index_most' => 'Hello World',
            'other'          => [
                'other' => 'other',
            ],
        ],
        'nested'             => [
            'other_object' => (object) [
                'apple',
                'banana',
                'coconut',
            ],
        ],
    ],
];

//Instance Class BOHBasicOutputHandler
//Ejemplo 1: Ejecucion con CSS independiente del proyecto
$output = new Output();                                           //instancia el output
//$output->loadBOHDesign('full', 'Tailwing');                       // establece que el ouput es independiente al Framwrork del Proyecto.
//$output->output($example_big_array);                              // ejecuta la salida

//ejecucion con CSS dependiente del proyecto
//Ejemplo 2: dependecia de BS4
//$output = new Output();                                           //instancia el output
//$output->setOptions(['css' => 'BS4', 'theme' => 'monokai']);      // establece que el ouput es dependiente de librerias CSS de BS4 usado en el proyecto y que la paleta de colores sera Monokai.
//$output->output($example_big_array);                              // ejecuta la salida

//Ejemplo 3: dependecia de TailWing
//$output->setOptions(['css' => 'tailwing', 'theme' => 'monokai']); // establece que el ouput es dependiente de librerias CSS de BS4 usado en el proyecto y que la paleta de colores sera Monokai.
//$output->output($example_big_array);                              // ejecuta la salida


/**
 * only to load and test the user interface with different Designs, do not
 * implement if your User Interface already implements a design framework.
 * Instead of this use setOptions()
 */
//echo '<h1>Hello World</h1>';
//


//$examplearray = ['int1' => 120, 120.25, 'datos' => ['int2' => '120', 'float' => '120.25']];
//Example 1: Output the Basic Html in Line


//echo '<pre>';
//echo var_dump($object);
//echo '</pre>';

//$output->output(['object_empty' => (object) [], 'object_fill' => $variable_class]);
//$output->setOptions(['css' => 'BS4', 'theme' => 'monokai']);
$output->output($example_big_array);

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

