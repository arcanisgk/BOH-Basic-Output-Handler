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


class Commons
{
    /**
     * repeater of String.
     *
     * @param  string|int|null  $haystack
     * @param  string|int|null  $needle
     *
     * @return false|int
     */
    public function checkStrPos($haystack, $needle)
    {
        return mb_strpos((string) $haystack, (string) $needle);
    }

    /**
     * repeater of String.
     *
     * @param $string
     * @return int
     */
    public function calclen($string): int
    {
        return mb_strlen((string) $string);
    }

    /**
     * captures a variable buffer and rhetoric as string
     *
     * @param  mixed  $data
     *
     * @return string
     */
    public function getBuffer($data): string
    {
        ob_start();
        var_dump($data);
        return ob_get_clean();
    }

    /**
     * repeater of String.
     *
     * @param  string  $character
     * @param  int  $repetitions
     *
     * @return string
     */
    protected function repeatChar(string $character, int $repetitions): string
    {
        return $repetitions > 0 ? str_repeat($character, $repetitions) : $character;
    }
}