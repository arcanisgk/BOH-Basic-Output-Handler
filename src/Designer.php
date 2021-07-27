<?php

/**
 * BOHBasicOutputHandler - Data output manager in PHP development environments.
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

namespace IcarosNet\BOHBasicOutputHandler;


class Designer
{
    /**
     * Determinate the default theme and css to apply.
     * @var array
     */
    const DEFAULTOPTIONS = ['theme' => 'monokai', 'css' => 'default'];

    /**
     * Default Palette Color of
     */
    const PALETTE_THEME = [
        'x-space'      => ['043,128,041', '099,099,099', '128,128,128', '072,094,187', '221,079,079', '000,000,000'],
        'mauro-dark'   => ['187,134,252', '250,250,250', '003,218,197', '255,204,255', '207,102,121', '018,018,018'],
        'natural-flow' => ['145,155,152', '030,156,107', '003,218,197', '006,156,004', '139,156,051', '004,041,003'],
        'monokai'      => ['117,113,094', '255,255,255', '102,217,239', '249,038,114', '230,219,116', "039,040,034"],
        'default'      => ['255,095,000', '000,000,255', '000,000,000', '000,175,000', '255,000,000', '255,255,255'],
    ];

    /**
     * @var string
     */
    public string $defCss = 'default';

    /**
     * @var string
     */
    public string $defTheme = 'monokai';

    /**
     * instantiate tool kit of Commons class.
     * @var Commons
     */
    protected Commons $commons;

    /**
     * Constructor of the Class Output
     */
    public function __construct()
    {
        $this->commons = new Commons();
    }

    /**
     *
     * @return array
     * @var $data array
     */
    public function getIndent(array $data): array
    {
        $indent          = [
            'main'     => (
                5 + $this->commons->getHighestCharAmountByKey($data, 'name') +
                $this->commons->getHighestCharAmountByKey($data, 'scope') +
                $this->commons->getHighestCharAmountByKey($data, 'visibility')),
            'value'    => $this->commons->getHighestCharAmountByKey($data, 'value'),
            'comments' => $this->commons->getHighestCharAmountByKey($data, 'comment') + 10,
            'min'      => 80,
            'max'      => 200,
        ];
        $indent['total'] = $indent['main'] + $indent['value'] + $indent['comments'];
        return $indent;
    }

    /**
     *
     * @param  array  $data
     * @param  array  $indent
     * @return string
     */
    public function getLayout(array $data, array $indent): string
    {
        //analisis del entorno
        //aplicar las variables de entorno y themes
        //obtener el texto parseado


        $stringparse = $this->commons->getStringFromArray($data, $indent);

        $theme_applied = '|Theme Applied: Default';
        $title_text    = $theme_applied . '|Output of Given Variable|';
        $title_len     = $this->commons->calculateLength($title_text);
        $total_width   = $indent['total'] < $indent['min'] ? $indent['min'] : $indent['total'];
        $title_text    = $this->commons->fillCharBoth(
            $title_text,
            $total_width,
            '='
        );
        $body_text     = '';
        $copyright     = $this->getCopyRight($indent, $total_width);


        return PHP_EOL . $title_text . PHP_EOL . $body_text . PHP_EOL . $copyright . PHP_EOL;
    }

    private function getCopyRight(array $indent, int $total_width): string
    {
        $copyright1 = $this->commons->fillCharBoth(
            '[BOH] Basic Output Handler for PHP - Copyright 20020 - ' . date('Y'),
            $total_width,
            '='
        );
        $copyright2 = $this->commons->fillCharBoth(
            'Open Source Project Developed by Icaros Net. S.A',
            $total_width,
            '='
        );
        $copyright3 = $this->commons->fillCharBoth(
            'URL: https://github.com/arcanisgk/BOH-Basic-Output-Handler',
            $total_width,
            '='
        );
        return $copyright1 . PHP_EOL . $copyright2 . PHP_EOL . $copyright3;
    }

    /**
     *
     * @return mixed
     * @var mixed
     */
    private function convertObject2Array($data)
    {
        $data = is_object($data) ? get_object_vars($data) : $data;
        return is_array($data) ? array_map(__METHOD__, $data) : $data;
    }

    private function calcDeepArray($data): int
    {
        $max_depth = 1;
        foreach ($data as $innerarray) {
            $max_depth = is_array($innerarray) ? count($innerarray) > $max_depth ? count($innerarray) : $max_depth : $max_depth;
        }
        return $max_depth;
    }

    private function calcIndentKey($data): int
    {
        ob_start();
        var_dump($data);
        $string = ob_get_clean();
        $max    = 0;
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $string) as $line) {
            $size = strripos($line, ']') - stripos($line, '[') - 1;
            $max  = $size > $max ? $size : $max;
        }
        return $max;
    }

    private function calcIndentVal($newArray, $len): int
    {
        if (is_array($newArray)) {
            foreach ($newArray as $child) {
                $len = $this->calcIndentVal($child, $len);
            }
        } else {
            if (is_resource($newArray)) {
                $temp = rtrim($this->commons->getBuffer($newArray));
                $len  = ($len >= $this->commons->calculateLength($temp)) ? $len : $this->commons->calculateLength($temp);
            } else {
                $newArray = (string) $newArray;
                $len      = ($len >= $this->commons->calculateLength($newArray)) ? $len : $this->commons->calculateLength($newArray);

            }
        }
        return $len;
    }
}