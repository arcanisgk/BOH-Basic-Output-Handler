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

use IcarosNetSA\BOH\OutputHandler;

/**
 * Validation of php version.
 * strictly equal to or greater than 7.4
 * a minor version will kill any script.
 */

if (!version_compare(PHP_VERSION, '7.4', '>=')) {
    die('IcarosNetSA\BOH requires PHP ver. 7.4 or higher');
}

/**
 * Description: Main function for setting output options.
 * @param  array  $options
 */

if (!function_exists('setOptionsChewData')) {
    function setOptionsChewData(array $options): void
    {
        if (empty($options)) {
            echo '"setOptionsDetailData" cannot receive empty arguments.';
        } else {
            OutputHandler::getInstance()->setOptions($options);
        }
    }
}

/**
 * Description: main function to expose the variable detailed
 * @param $args
 * @return array|void
 */

if (!function_exists('chewData')) {
    function chewData(...$args)
    {
        if (empty($args)) {
            echo '"chewData" must receive at least 1 argument.';
            exit;
        }
        $result = [];
        if (1 < func_num_args()) {
            foreach ($args as $arg) {
                $result[] = OutputHandler::getInstance()->receiverData($arg);
            }
        } else {
            $result = OutputHandler::getInstance()->receiverData($args[0]);
        }
        return $result;
    }
}

/**
 * Description: Secondary function to expose the variable detailed based in CLI enviroment
 * @param $args
 * @return array|void
 */

if (!function_exists('chewDataCLI')) {
    function chewDataCLI(...$args)
    {
        if (empty($args)) {
            echo '"chewData" must receive at least 1 argument.';
            exit;
        }
        $result = [];
        if (1 < func_num_args()) {
            foreach ($args as $arg) {
                $result[] = OutputHandler::getInstance()->receiverData($arg);
            }
        } else {
            $result = OutputHandler::getInstance()->receiverData($args[0]);
        }
        return $result;
    }
}
