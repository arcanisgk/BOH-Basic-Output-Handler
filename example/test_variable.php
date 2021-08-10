<?php

trait talkWorld
{
    public function sayHello()
    {
        parent::sayHello();
        echo 'World!';
    }
}

class Base
{
    public function sayHello()
    {
        echo '¡Hello ';
    }
}

class MyHelloWorld extends Base
{
    use talkWorld;

    public function __construct()
    {
    }
}

$class_trait_instance = new MyHelloWorld();


/**
 * FooBar is an example class.
 */
class FooBar
{
    public const CONST_ARRAY = ['a' => 1, 'b' => 20];
    protected const CONST_STRING = 'Constant String example';
    private const CONST_INT = 5000;
    public static string $public_static_string_prop = 'Example Hello World! 2';
    protected static bool $protected_static_boolean_prop = true;
    private static object $private_static_object_prop;
    private static object $private_static_object_prop_not_initialized;
    private static array $private_static_array_long_name = ['X' => 12, 'Y' => 24];
    public MyHelloWorld $class_traits;
    public string $public_string_prop = 'Example Hello World!';
    public $file_read_property;
    protected int $pro_int_prop = 10;
    private array $private_array_long_name = ['a' => 1, 'b' => 2];

    public function __construct()
    {
        $file_name                = "file.txt";
        $this->file_read_property = fopen($file_name, "r");
        self::fooMethod3();
        //$this->class_traits = new MyHelloWorld();
    }

    public static function fooMethod3(): string
    {
        self::$private_static_object_prop                  = new stdClass();
        self::$private_static_object_prop->menber_property = 'Sr Smith';
        return self::$public_static_string_prop;
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

    private final function fooMethod4(array $example, object $class, string $event): void
    {

    }
}

/**
 * $variable_class is a variable storage of instance class FooBar.
 */
$variable_class = new FooBar();

/**
 *
 */
$json_response    = file_get_contents('https://randomuser.me/api/');
$object_from_json = json_decode($json_response);

/**
 * $object is a short variable to use as an example stdClass.
 */
$object                  = new stdClass();
$object->int_property    = 1000;
$object->string_property = 'Hello World 1';
$object->float_property  = 3.1416;
$object->array_property  = ['Hello World 2', 'test', 'indice_' => ['12', 'float_data' => 12.45]];
$object->object_property = (object) ['index' => 125, 'Hello World3', ['other' => 3.14, (object) ['internal_object_prop' => 'value']]];


/**
 * $object_casted is a short variable to use as an example stdClass cast.
 */

$object_casted = (object) ['int1' => 120, 120.25, 'data' => ['int2' => '120', 'float' => '120.25']];


/**
 * $example_big_array is a large array variable to use as an example.
 */
$example_big_array = [
    'null'                   => null,
    'null_text'              => 'null',
    'integer'                => 10,
    'integer_text'           => '10',
    'float'                  => 20.35,
    'float_text'             => '20.35',
    'string'                 => 'Hello World',
    'date_1'                 => '2021-01-17',
    'date_2'                 => '2021-Jan-17',
    'hour_1'                 => '6:31:00 AM',
    'hour_2'                 => '17:31:00',
    'datetime_1'             => '2021-01-17 17:31:00',
    'datetime_2'             => '2021-Jan-17 6:31:00 AM',
    'datetime_3'             => '2021-01-17 6:31:00 AM',
    'datetime_4'             => '2021-Jan-17 17:31:00',
    'currency_1'             => '1.45$',
    'currency_2'             => 'db£ 1.45 ₹',
    'array_empty'            => [],
    'array_node'             => [
        'boolean_true'       => true,
        'boolean_false'      => false,
        'boolean_true_text'  => 'true',
        'boolean_false_text' => 'false',
    ],
    'array_multidimensional' => [
        'array_lvl_1' => [
            'array_lvl_2' => [
                'member_1',
                'member_2',
                'member_3',
                'array_lvl_3' => [
                    'member_1'    => 'Hello World',
                    'array_lvl_4' => [
                        'member_1_more_text_index' => 'other',
                    ],
                ],
            ],
        ],
    ],
    'resource'               => curl_init(),
    'object_empty'           => (object) [],
    'object_example'         => $object,
    'object_casted'          => $object_casted,
    'class_stored'           => $variable_class,
    'trait_example'          => $class_trait_instance,
    'json_object_from_api'   => $object_from_json,
    'special_characters'     => [
        'str_4_ch1' => 'ABCW',
        'str_4_ch2' => 'عباس',
        'str_4_ch3' => '正在下雨',
        'str_4_ch4' => 'ождж',
        'str_4_ch5' => '雨が降降',
        'str_4_ch6' => 'רגשם',
    ],
];
