<?php

/**
 * BOH - Data Output Manager in PHP Development Environments.
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

namespace IcarosNetSA\BOH;

/**
 * DescriptionAnalyzer Class.
 *
 */
class DescriptionAnalyzer
{
    /**
     * Description: Library configuration variable.
     * + set if we want to use indentation between name, values and comments.
     * - 'indent'
     *      - true (default)
     *      - false
     * @var bool
     */
    public bool $add_indent = true;

    /**
     * Description: Library configuration variable.
     * + sets the character used for the assignment.
     * - '=' primitive
     * - '=>' object, array
     * @var string
     */
    public string $assignment = '=';

    /**
     * Description: instantiate tool kit of Commons class.
     * @var Commons
     */
    private Commons $commons;

    /**
     * Description: Constructor of the Class Analyzer
     */
    public function __construct()
    {
        $this->commons = new Commons();
    }

    /**
     * Description: it recursively takes the description
     * arrangement and requests the analysis of the nodes according to how it is,
     * returning a string formatted according to the emitted indentation.
     * @param  $indent
     * @param  $data
     * @param  int  $in
     * @param  null  $k
     * @param  int  $c
     * @return string
     */
    public function getStringFromDescription($indent, $data, int $in = 0, $k = null, int $c = 0): string
    {
        if (isset($data['type']) && $data['type'] !== 'array' && $data['type'] !== 'object') {
            return $this->getStringFromDescriptionOfPrimitive($indent, $data, $in, $k, $c);
        } elseif (isset($data['type']) && $data['type'] == 'array') {
            if (!isset($data['value'])) {
                return $this->getStringFromDescriptionEmptyNode($indent, $data, $in, $k, $c);
            } else {
                return $this->getStringFromDescriptionOpenNode($indent, $data, $in, $k)
                    . $this->getStringFromDescriptionNavigateArray($indent, $data['value'], $in + 4, $c + 1)
                    . $this->getStringFromDescriptionCloseNode($indent, $in, $c);
            }
        } else {
            if (!isset($data['properties']) && !isset($data['constants']) && !isset($data['methods'])) {
                return $this->getStringFromDescriptionEmptyNode($indent, $data, $in, $k, $c);
            } else {
                return $this->getStringFromDescriptionOpenNode($indent, $data, $in, $k)
                    . $this->getStringFromDescriptionNavigateObject($indent, $data, $in + 4, $c + 1)
                    . $this->getStringFromDescriptionCloseNode($indent, $in, $c);
            }
        }
    }

    /**
     * Description: this method takes a primitive value and uses the
     * description nodes to create a line.
     * @param  $indent
     * @param  $data
     * @param  int  $in
     * @param  null  $k
     * @param  $c
     * @return string
     */
    private function getStringFromDescriptionOfPrimitive($indent, $data, int $in, $k, $c): string
    {
        $line_array = [
            [
                'char'  => '',
                'count' => $in,
            ], [
                'char'  => ($k === null ? '$given_var' : "'$k'"),
                'count' => $this->getInLineIndent($indent['main'] - $in),
            ], [
                'char'  => $this->assignment . ' ' . $data['value'] . ($c <= 0 ? ';' : ','),
                'count' => $this->getInLineIndent($indent['value']),
            ], [
                'char'  => ' // ' . $data['comment'],
                'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
            ],
        ];
        return $this->commons->lineValidation(
                $this->populateLine($line_array),
                $indent,
                $this->add_indent
            ) . PHP_EOL;
    }

    /**
     * Description: validates if the line will be indented to what value.
     * @param  int  $value
     * @return int
     */
    private function getInLineIndent(int $value): int
    {
        return $this->add_indent ? $value : 0;
    }

    /**
     * Description: It takes a Line array and converts it to a character line.
     * @param  array  $line_array
     * @return string
     */
    private function populateLine(array $line_array): string
    {
        return array_reduce(array_map([$this, "getPopulatedSpaced"], $line_array), [$this, "getLine"]);
    }

    /**
     * Description: This method is used for analysis and gap filling of Empty Array/object.
     * @param  array  $indent
     * @param  $data
     * @param  int  $in
     * @param  $k
     * @param  int  $c
     * @return string
     */
    private function getStringFromDescriptionEmptyNode(array $indent, $data, int $in, $k, int $c): string
    {
        $node       = (isset($data['class']) ? '(object)' : '');
        $ending     = $this->nodeEnding($data);
        $line_array = [
            [
                'char'  => '',
                'count' => $in,
            ], [
                'char'  => ($k === null ? '$given_var' : "'$k'"),
                'count' => $this->getInLineIndent($indent['main'] - $in),
            ], [
                'char'  => '=' . $node . '[]' . ($c <= 0 ? ';' : $ending),
                'count' => $this->getInLineIndent($indent['value']),
            ], [
                'char'  => ' // ' . $data['comment'],
                'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
            ],
        ];
        return $this->commons->lineValidation(
                $this->populateLine($line_array),
                $indent,
                $this->add_indent
            ) . PHP_EOL;
    }

    /**
     * Description: validates whether the current node must have an End of an
     * analysis character or is a recursive sub-node.
     * @param  $data
     * @return string
     */
    private function nodeEnding($data): string
    {
        if ((isset($data['value']) && !is_array($data['value']))
            || (isset($data['type']) && $data['type'] == 'array' && !isset($data['value']))
            || (isset($data['type']) && $data['type'] == 'object' && !isset($data['properties']) && !isset($data['constants']) && !isset($data['methods']))
            || (isset($data['class']))
        ) {
            return ';';
        } else {
            return ',';
        }
    }

    /**
     * Description: This method is used for parsing and filling gaps for opening Array/Object nodes.
     * @param  array  $indent
     * @param  $data
     * @param  int  $in
     * @param  $k
     * @return string
     */
    private function getStringFromDescriptionOpenNode(array $indent, $data, int $in, $k): string
    {
        $type       = (isset($data['type']) ? '' : '(object)');
        $line_array = [
            [
                'char'  => '',
                'count' => $in,
            ], [
                'char'  => ($k === null ? '$given_var' : "'$k'"),
                'count' => $this->getInLineIndent($indent['main'] - $in),
            ], [
                'char'  => '=' . $type . '[',
                'count' => $this->getInLineIndent($indent['value']),
            ], [
                'char'  => ' // ' . $data['comment'],
                'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
            ],
        ];
        return $this->commons->lineValidation(
                $this->populateLine($line_array),
                $indent,
                $this->add_indent
            ) . PHP_EOL;
    }

    /**
     * Description:
     * @param  array  $indent
     * @param  $data
     * @param  int  $in
     * @param  int  $c
     * @return string
     */
    private function getStringFromDescriptionNavigateArray(array $indent, $data, int $in, int $c): string
    {
        $buffer = [];
        foreach ($data as $key => $sub_value) {
            $buffer[] = $this->getStringFromDescription($indent, $sub_value, $in, $key, $c + 1);
        }
        return implode(PHP_EOL, $buffer) . PHP_EOL;
    }

    /**
     * Description: This method is used to navigate through an array; analyze
     * and fill in the gaps of the nodes of the Array.
     * @param  array  $indent
     * @param  int  $in
     * @param  int  $c
     * @return string
     */
    private function getStringFromDescriptionCloseNode(array $indent, int $in, int $c): string
    {
        $line_array = [
            [
                'char'  => '',
                'count' => $in,
            ], [
                'char'  => ']' . ($c <= 0 ? ';' : ','),
                'count' => $this->getInLineIndent($indent['value']),
            ], [
                'char'  => '',
                'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
            ],
        ];
        return $this->commons->lineValidation(
                $this->populateLine($line_array),
                $indent,
                $this->add_indent
            ) . PHP_EOL;
    }

    /**
     * Description: This method is used to navigate through an object; analyze
     * and fill in the gaps of the nodes of the object.
     * @param  array  $indent
     * @param  $data
     * @param  int  $in
     * @param  int  $c
     * @return string
     */
    private function getStringFromDescriptionNavigateObject(array $indent, $data, int $in, int $c): string
    {
        return (!isset($data['properties']) ? '' : $this->getStringFromDescriptionNavigateObjectProperties($indent, $data['properties'], $in, $c + 1))
            . (!isset($data['constants']) ? '' : $this->getStringFromDescriptionNavigateObjectConstants($indent, $data['constants'], $in, $c + 1))
            . (!isset($data['methods']) ? '' : $this->getStringFromDescriptionNavigateObjectMethods($indent, $data['methods'], $in, $c + 1));
    }

    /**
     * Description: This method is used to obtain an analysis of the properties
     * of an object; and get it in string format with space filling.
     * @param  array  $indent
     * @param  $data
     * @param  int  $in
     * @param  int  $c
     * @return string
     */
    private function getStringFromDescriptionNavigateObjectProperties(array $indent, $data, int $in, int $c): string
    {
        $buffer            = [];
        $type              = '(property)';
        $end_Line_Property = $this->commons->lineValidation(
                $this->populateLine(
                    [
                        [
                            'char'  => '',
                            'count' => $in,
                        ],
                        [
                            'char'  => ']' . ($c <= 0 ? ';' : ','),
                            'count' => $this->getInLineIndent($indent['value']),
                        ],
                        [
                            'char'  => '',
                            'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                        ],
                    ]
                ),
                $indent,
                $this->add_indent
            ) . PHP_EOL;
        foreach ($data as $key => $property) {
            if (gettype($property['value']) != 'array') {
                $line_array = [
                    [
                        'char'  => '',
                        'count' => $in,
                    ], [
                        'char'  => ($key === null ? "'unknown'" : "'" . $property['name'] . "'"),
                        'count' => $this->getInLineIndent($indent['main'] - $in),
                    ], [
                        'char'  => $this->assignment . ' ' . $property['value'] . ($c <= 0 ? ';' : ','),
                        'count' => $this->getInLineIndent($indent['value']),
                    ], [
                        'char'  => ' // ' . $type . ' ' . $property['comment'] . ' (scope: ' . $property['scope'] . ', visibility: ' . $property['visibility'] . ').',
                        'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                    ],
                ];
                $buffer[]   = $this->commons->lineValidation(
                        $this->populateLine($line_array),
                        $indent,
                        $this->add_indent
                    ) . PHP_EOL;
            } elseif ($property['type'] == 'array') {
                $line_array = [
                    [
                        'char'  => '',
                        'count' => $in,
                    ], [
                        'char'  => ($key === null ? '$given_var' : "'$key'"),
                        'count' => $this->getInLineIndent($indent['main'] - $in),
                    ], [
                        'char'  => $this->assignment . '[',
                        'count' => $this->getInLineIndent($indent['value']),
                    ], [
                        'char'  => ' // ' . $type . ' ' . $property['comment'] . ' (scope: ' . $property['scope'] . ', visibility: ' . $property['visibility'] . ').',
                        'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                    ],
                ];
                $buffer[]   = $this->commons->lineValidation(
                        $this->populateLine($line_array),
                        $indent,
                        $this->add_indent
                    ) . PHP_EOL;
                $buffer[]   = $this->getStringFromDescriptionNavigateArray($indent, $property['value'], $in + 4, $c + 1);
                $buffer[]   = $end_Line_Property;
            } elseif ($property['type'] == 'object') {
                $line_array = [
                    [
                        'char'  => '',
                        'count' => $in,
                    ], [
                        'char'  => ($key === null ? '$given_var' : "'$key'"),
                        'count' => $this->getInLineIndent($indent['main'] - $in),
                    ], [
                        'char'  => $this->assignment . '(object)[',
                        'count' => $this->getInLineIndent($indent['value']),
                    ], [
                        'char'  => ' // ' . $type . ' ' . $property['comment'] . ' (scope: ' . $property['scope'] . ', visibility: ' . $property['visibility'] . ').',
                        'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                    ],
                ];
                $buffer[]   = $this->commons->lineValidation(
                        $this->populateLine($line_array),
                        $indent,
                        $this->add_indent
                    ) . PHP_EOL;
                $buffer[]   = $this->getStringFromDescriptionNavigateObject($indent, $property['value'], $in + 4, $c + 1);
                $buffer[]   = $end_Line_Property;

            }
        }
        return implode(PHP_EOL, $buffer);
    }

    /**
     * Description: This method is used to obtain an analysis of the constants
     * of an object; and get it in string format with space filling.
     * @param  array  $indent
     * @param  $data
     * @param  int  $in
     * @param  int  $c
     * @return string
     */
    private function getStringFromDescriptionNavigateObjectConstants(array $indent, $data, int $in, int $c): string
    {
        dump($data);
        $buffer            = [];
        $type              = '(constant)';
        $end_Line_Property = $this->commons->lineValidation(
                $this->populateLine(
                    [
                        [
                            'char'  => '',
                            'count' => $in,
                        ],
                        [
                            'char'  => ']' . ($c <= 0 ? ';' : ','),
                            'count' => $this->getInLineIndent($indent['value']),
                        ],
                        [
                            'char'  => '',
                            'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                        ],
                    ]
                ),
                $indent,
                $this->add_indent
            ) . PHP_EOL;
        foreach ($data as $key => $constant) {
            dump($constant);

            if (gettype($constant['value']) != 'array') {
                /*
                $line_array = [
                    [
                        'char'  => '',
                        'count' => $in,
                    ], [
                        'char'  => ($key === null ? "'unknown'" : "'" . $property['name'] . "'"),
                        'count' => $this->getInLineIndent($indent['main'] - $in),
                    ], [
                        'char'  => $this->assignment . ' ' . $property['value'] . ($c <= 0 ? ';' : ','),
                        'count' => $this->getInLineIndent($indent['value']),
                    ], [
                        'char'  => ' // ' . $type . ' ' . $property['comment'] . ' (scope: ' . $property['scope'] . ', visibility: ' . $property['visibility'] . ').',
                        'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                    ],
                ];
                $buffer[]   = $this->commons->lineValidation(
                        $this->populateLine($line_array),
                        $indent,
                        $this->add_indent
                    ) . PHP_EOL;
                */
            } elseif ($constant['type'] == 'array') {
                /*
                $line_array = [
                    [
                        'char'  => '',
                        'count' => $in,
                    ], [
                        'char'  => ($key === null ? '$given_var' : "'$key'"),
                        'count' => $this->getInLineIndent($indent['main'] - $in),
                    ], [
                        'char'  => $this->assignment . '[',
                        'count' => $this->getInLineIndent($indent['value']),
                    ], [
                        'char'  => ' // ' . $type . ' ' . $property['comment'] . ' (scope: ' . $property['scope'] . ', visibility: ' . $property['visibility'] . ').',
                        'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                    ],
                ];
                $buffer[]   = $this->commons->lineValidation(
                        $this->populateLine($line_array),
                        $indent,
                        $this->add_indent
                    ) . PHP_EOL;
                $buffer[]   = $this->getStringFromDescriptionNavigateArray($indent, $property['value'], $in + 4, $c + 1);
                $buffer[]   = $end_Line_Property;
                */
            } elseif ($constant['type'] == 'object') {
                /*
                $line_array = [
                    [
                        'char'  => '',
                        'count' => $in,
                    ], [
                        'char'  => ($key === null ? '$given_var' : "'$key'"),
                        'count' => $this->getInLineIndent($indent['main'] - $in),
                    ], [
                        'char'  => $this->assignment . '(object)[',
                        'count' => $this->getInLineIndent($indent['value']),
                    ], [
                        'char'  => ' // ' . $type . ' ' . $property['comment'] . ' (scope: ' . $property['scope'] . ', visibility: ' . $property['visibility'] . ').',
                        'count' => $this->getInLineIndent($indent['total'] - $indent['comments']),
                    ],
                ];
                $buffer[]   = $this->commons->lineValidation(
                        $this->populateLine($line_array),
                        $indent,
                        $this->add_indent
                    ) . PHP_EOL;
                $buffer[]   = $this->getStringFromDescriptionNavigateObject($indent, $property['value'], $in + 4, $c + 1);
                $buffer[]   = $end_Line_Property;
                */
            }

        }
        return implode(PHP_EOL, $buffer);
    }

    /**
     * Description: This method is used to obtain an analysis of the methods
     * of an object; and get it in string format with space filling.
     * @param  array  $indent
     * @param  $data
     * @param  int  $in
     * @param  int  $c
     * @return string
     */
    private function getStringFromDescriptionNavigateObjectMethods(array $indent, $data, int $in, int $c): string
    {
        return '';
    }

    /**
     * Description: This method is used for recursive analysis and gap filling.
     * @param  array  $line_constructor
     * @return string
     */
    private function getPopulatedSpaced(array $line_constructor): string
    {
        return $this->commons->fillCharRight(
            $line_constructor['char'],
            $line_constructor['count'],
            ' ');
    }

    /**
     * Description: This method is used for line concatenated recursive analysis.
     * @param  $buffer
     * @param  string  $current
     * @return string
     */
    private function getLine($buffer, string $current): string
    {
        $buffer .= $current;
        return $buffer;
    }
}