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


class Designer extends Validation
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

    public function __construct()
    {

    }

    /**
     *
     * @return array
     * @var mixed
     */
    protected function getIndent($data): array
    {
        $indents = ['key' => 0, 'val' => 0];
        if (is_array($data) || is_object($data)) {
            $newArray       = $this->convertObject2Array($data);
            $deep           = $this->calcDeepArray($newArray) * 4;
            $indents['key'] = $this->calcIndentKey($data) + $deep;
            $indents['val'] = $this->calcIndentVal($newArray, 0) + (int) ($deep / 2);
        } else {
            $indents = ['key' => $this->calclen('variable'), 'val' => $this->calclen($data)];
        }
        return $indents;
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
            foreach ($newArray as $key => $child) {
                $len = $this->calcIndentVal($child, $len);
            }
        } else {
            if (is_resource($newArray)) {
                $temp = rtrim($this->getBuffer($newArray));
                $len  = ($len >= $this->calclen($temp)) ? $len : $this->calclen($temp);
            } else {
                $newArray = (string) $newArray;
                $len      = ($len >= $this->calclen($newArray)) ? $len : $this->calclen($newArray);
            }
        }
        return $len;
    }
}