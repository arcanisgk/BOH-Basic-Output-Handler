<?php

/**
 * src - Data toPlain manager in PHP development environments.
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

namespace IcarosNet\BOH;

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
     * Constructor of the Class OutputHandler
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
        $name             = $this->commons->getHighestCharAmountByKey($data, 'name');
        $value            = $this->commons->getHighestCharAmountByKey($data, 'value');
        $param            = $this->commons->getHighestCharAmountByKey($data, 'params');
        $deep             = $this->calcDeepArray($data) * 4;
        $main             = $name + $deep;
        $value_calculated = $value < $param ? $param : $value;
        $indent           = [
            'main'     => $main < 8 ? 10 + $main : $main + 2,
            'value'    => $value_calculated + 4,
            'comments' => $this->commons->getHighestCharAmountByKey($data, 'comment') + 5,
            'min'      => 80,
            'max'      => 200,
        ];
        $total            = $indent['main'] + $indent['value'] + $indent['comments'];
        $total            = $total < $indent['min'] ? $indent['min'] : $total;
        $total            = $total > $indent['max'] ? $indent['max'] : $total;
        $indent['total']  = $this->commons->checkNumber($total) ? $total : $total + 1;
        return $indent;
    }

    /**
     *
     * @param  array  $data
     * @return int
     */
    private function calcDeepArray(array $data): int
    {
        $max_depth = 0;
        foreach ($data as $value) {
            if (is_array($value)) {
                $depth = $this->calcDeepArray($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return ($max_depth - 1) < 0 ? 4 : ($max_depth - 1);
    }

    /**
     *
     * @param  array  $data
     * @param  array  $indent
     * @return string
     */
    public function getLayout(array $data, array $indent): string
    {
        $total_width = $indent['total'] < $indent['min'] ? $indent['min'] : $indent['total'];
        $output      = $this->commons->getStringFromArray($indent, $data['analyzed']);
        $output      = $this->commons->cleanLinesString($output, $total_width);
        $body_text   = highlight_string("<?php\n" . $output . "?>", true);
        //$body_text  = $this->commons->removePHPStart($body_text);
        $title_text = $this->getTitle($total_width, $data);
        $copyright  = $this->getCopyRight($total_width);
        return $title_text . '<br>' . $body_text . '<br>' . $copyright;
    }

    /**
     *
     * @param  int  $total_width
     * @param  array  $data
     * @return mixed
     */
    private function getTitle(int $total_width, array $data): string
    {
        $theme_applied = ' | Theme Applied: Default ';
        $title_text    = $theme_applied . '| OutputHandler of Given Variable | Type: ' . $data['type'] . ' | ';
        return $this->commons->fillCharBoth(
            $title_text,
            $total_width,
            '='
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
            ' [src] Basic OutputHandler Handler for PHP - Copyright 20020 - ' . date('Y') . ' ',
            $total_width,
            '='
        );
        $copyright2 = $this->commons->fillCharBoth(
            ' Open Source Project Developed by Icaros Net. S.A ',
            $total_width,
            '='
        );

        $copyright_indent = (int) floor(($total_width - 44) / 2);
        $copyright3       = $this->commons->repeatChar('=', $copyright_indent)
            . ' URL:  <a href="https://github.com/IcarosNetSA/src-Basic-OutputHandler-Handler">IcarosNetSA/src-Basic-OutputHandler-Handler</a> '
            . $this->commons->repeatChar('=', (($copyright_indent * 2) < $total_width ? $copyright_indent + 1 : $copyright_indent));
        return $copyright1 . '<br>' . $copyright2 . '<br>' . $copyright3;
    }
}