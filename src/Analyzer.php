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

use ReflectionException;

/**
 * Analyzer Class.
 *
 */
class Analyzer
{
    /**
     * Description: instantiate tool kit of Commons class.
     * @var Commons
     */
    private Commons $commons;

    /**
     * Description: instantiate tool kit of Commons class.
     * @var CustomReflection
     */
    private CustomReflection $custom_reflection;

    /**
     * Description: instantiate tool kit of DescriptionAnalyzer class.
     * @var DescriptionAnalyzer
     */
    private DescriptionAnalyzer $description_analyzer;


    /**
     * Description: Constructor of the Class Analyzer
     */
    public function __construct()
    {
        $this->commons              = new Commons();
        $this->description_analyzer = new DescriptionAnalyzer();
        $this->custom_reflection    = new CustomReflection($this);
    }

    /**
     * Description: Method responsible for returning the Variable Description Array.
     * @param $data
     * @return array
     * @throws ReflectionException
     */
    public function getVariableDescription($data): array
    {
        return [
            'type'     => strtolower(gettype($data)),
            'analyzed' => $this->variableAnalyzer($data),
        ];
    }

    /**
     * Description: This method uses the passed data to analyze and extract all
     * the possible information, returning a descriptive array different from
     * the original.
     * @param $data
     * @param  null|string  $key
     * @return array
     * @throws ReflectionException
     */
    public function variableAnalyzer($data, string $key = null): array
    {
        if (gettype($data) === 'array') {
            return $this->descriptionArray($data, $key);
        } elseif (gettype($data) === 'object') {
            return $this->custom_reflection->descriptionObject($data);
        } else {
            $evaluation = $this->commons->descriptionVariable($data, (string) $key);
            if (!isset($evaluation['name']) || $evaluation['name'] === '') {
                unset($evaluation['name']);
            }
            return $evaluation;
        }
    }

    /**
     * Description: it recursively takes an array and iterates it in search of
     * new elements, objects or nodes, requests the evaluation and returns what
     * is obtained.
     * @param $data
     * @param  null  $key
     * @return array
     * @throws ReflectionException
     */
    private function descriptionArray($data, $key): array
    {
        $description = [];
        foreach ($data as $sk => $value) {
            $description['value'][$sk] = $this->variableAnalyzer($value, (string) $sk);
        }
        $evaluation = $this->commons->descriptionVariable($data, (string) $key);
        if (!isset($evaluation['name']) || $evaluation['name'] === '') {
            unset($evaluation['name']);
        }
        unset($evaluation['value']);
        return array_merge($description, $evaluation);
    }

    /**
     * Description: this method establishes some necessary variables and
     * requests the analysis of the description in a string format.
     * @param  array  $indent
     * @param  array  $description
     * @return string
     */
    public function getAnalysisDescription(array $indent, array $description): string
    {
        $this->description_analyzer->assignment = $this->getAssignmentSign($description['type']);
        $this->description_analyzer->add_indent = $indent['add'];
        return $this->description_analyzer->getStringFromDescription($indent, $description['analyzed']);
    }

    /**
     * Description: this method evaluates and sets the assignment symbol to use
     * @param  string  $type
     * @return string
     */
    private function getAssignmentSign(string $type): string
    {
        return in_array($type, ['array', 'object']) ? '=>' : '=';
    }
}