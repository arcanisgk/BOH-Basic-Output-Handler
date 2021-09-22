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
     * Description: Library configuration variable.
     * + Establishes the color palette that is used in the view / display of the information.
     * - 'theme':
     *      - 'default' (default)
     *      - 'monokai'
     *      - 'x-space'
     *      - 'mauro-dark'
     *      - 'natural-flow'
     *      - 'vs-code'
     *      - 'red-redemption'
     *      - 'gray-scale'
     * @var string
     */
    public string $theme = 'default';

    /**
     * Description: instantiate tool kit of Commons class.
     * @var Commons
     */
    private Commons $commons;

    /**
     * Description: Library configuration variable.
     * + Determines which of the web environments should run in the view.
     * - 'env': supported list
     *      - 'plain' (default)
     *      - 'web'
     *      - 'json'
     * @var string
     */
    private string $env = 'plain';

    /**
     * Description: Constructor of the Class Analyzer
     */
    public function __construct()
    {
        $this->commons = new Commons();
    }

    /**
     * Description: Method responsible for returning the indent to parse array to String.
     * @param $data array
     * @param  bool  $add_indent
     * @param  int  $deep
     * @return array
     */
    public function getIndent(array $data, bool $add_indent, int $deep): array
    {
        $name             = $this->commons->getHighestCharacterAmountByKey($data, 'name');
        $value            = $this->commons->getHighestCharacterAmountByKey($data, 'value');
        $param            = $this->commons->getHighestCharacterAmountByKey($data, 'params');
        $main             = $name + $deep;
        $value_calculated = $value < $param ? $param : $value;
        $indent           = [
            'main'     => $main < 8 ? 10 + $main : 2 + $main,
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

    public function stringHighlight($description_string): string
    {
        $buffer    = highlight_string("<?php " . $description_string, true);
        $buffer    = explode("&lt;?php", $buffer);
        $buffer[1] = preg_replace('/&nbsp;/', '', $buffer[1], 1);
        return implode('', $buffer);
    }

    public function addWrap($description_string, $indent, $type): string
    {
        $total_width = $indent['total'] < $indent['min'] ? $indent['min'] : $indent['total'];
        $title_text  = $this->getTitle($total_width, $type);
        $copyright   = $this->getCopyRight($total_width);
        return $this->commons->spaceJustify($title_text
            . PHP_EOL . $description_string
            . PHP_EOL . $copyright,
            $total_width);
    }

    /**
     *
     * @param  int  $total_width
     * @param  string  $type
     * @return mixed
     */
    private function getTitle(int $total_width, string $type): string
    {
        $theme_applied = '// Theme: ' . ($this->theme == 'default' ? 'Default ' : '(' . ucfirst($this->theme) . ')');
        $title_text    = $theme_applied . '| Given Variable | Type: ' . $type;
        return $this->commons->fillCharBoth(
            $title_text,
            $total_width,
            ''
        );
    }

    /**
     *
     * @param  int  $total_width
     * @return mixed
     */
    private function getCopyRight(int $total_width): string
    {
        $copyright1 = $this->commons->fillCharBoth(
            '// [BOH] Basic Output Handler for PHP - Copyright 2020 - ' . date('Y') . ' ',
            $total_width,
            ''
        );
        $copyright2 = $this->commons->fillCharBoth(
            '// Open Source Project Developed by Icaros Net. S.A ',
            $total_width,
            ''
        );
        return $copyright1 . PHP_EOL . $copyright2;
    }

    public function wrapElement(string $result, string $bg): string
    {
        return '<div style="padding: 10px; background-color: rgb(' . $bg . ');border-radius: 10px;">' . $result . '</div>';
    }

    public function addFull(string $result): string
    {

        return '';
    }

    public function addModal(string $result): string
    {
        return '';
    }
}