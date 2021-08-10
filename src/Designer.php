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
 * Designer Class.
 *
 */
class Designer
{
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
     * Description: Method responsible for returning the indent to parse array to String.
     * @param  bool  $add_indent
     * @param $data array
     * @return array
     */
    public function getIndent(array $data, bool $add_indent): array
    {
        $name             = $this->commons->getHighestCharacterAmountByKey($data, 'name');
        $value            = $this->commons->getHighestCharacterAmountByKey($data, 'value');
        $param            = $this->commons->getHighestCharacterAmountByKey($data, 'params');
        $deep             = ((int) ceil($this->commons->calculateDeepArray($data['analyzed']) / 2)) * 4;
        $main             = $name + $deep;
        $value_calculated = $value < $param ? $param : $value;
        $indent           = [
            'main'     => $main < 8 ? 10 + $main : $main + 2,
            'value'    => $value_calculated + 4,
            'comments' => $this->commons->getHighestCharacterAmountByKey($data, 'comment') + 12,
            'min'      => 80,
            'max'      => 200,
        ];
        $total            = $indent['main'] + $indent['value'] + $indent['comments'];
        $total            = $total < $indent['min'] ? $indent['min'] : $total;
        $total            = $total > $indent['max'] ? $indent['max'] : $total;
        $indent['total']  = $this->commons->isPair($total) ? $total : $total + 1;
        $indent['add']    = $add_indent;
        return $indent;
    }
}