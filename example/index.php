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

/**
 * This example shows how the BOH class and its methods are declared.
 */

use IcarosNetSA\BOH\OutputHandler;

const PATH          = __DIR__ . '\..\vendor\autoload.php';
const TEST_VARIABLE = __DIR__ . '\test_variable.php';


/**
 * Validate the existence of the Class File [BOH].
 */
if (file_exists(PATH)) {
    /**
     * import the class [BOH] to be used as an example.
     */
    require_once PATH;
} else {
    echo 'This library "[BOH] Basic OutputHandler Handler for PHP" requires composer installation and autoload; run composer install command in your root.';
    die;
}

/**
 * Validate the existence of the File test_variable.php.
 */
if (!file_exists(TEST_VARIABLE)) {
    echo '"[BOH] Basic OutputHandler Handler for PHP" need of test_variable.php file to perform test output.';
    die;
} else {

    /**
     * import the file test_variable.php to be used as an example.
     */
    require_once TEST_VARIABLE;

    /**
     * Instance Class [BOH] to be used as Example.
     */
    $output = new OutputHandler();

    /**
     * Options Enabled.
     *
     * + Determines which of the web environments should run in the view.
     * - 'env': supported list
     *      - 'plain' (default)
     *      - 'web'
     *      - 'json'
     *
     * + Preload all the html, javascript in a separate web module from the original, it has no effect in cli environment.
     * - 'build': supported list
     *      - 'default' (default)
     *      - 'full'
     *
     * + Used only for the web and json environment, it determines which HTML template will be loaded based on the chosen css framework.
     * - 'css': supported list
     *      - 'default' (default)
     *      - 'bs5'
     *      - 'bs4'
     *      - 'bulma'
     *      - 'foundation'
     *      - 'jquery-ui'
     *      - 'semantic-ui'
     *      - 'uikit'
     *      - 'materialize'
     *      - 'pure'
     *      - 'tailwind'
     *
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
     *
     * + set if we want to use indentation between name, values and comments.
     * - 'indent'
     *      - true (default)
     *      - false
     */

    $output->setOptions(['indent' => true, 'return' => true]);

    /**
     * Use the variables in /example/test_variable.php to get a test view of each type of data that can be analyzed.
     * @var $example_big_array
     *
     */

    $result = '';
    foreach ($example_big_array as $key => $value) {
        $result .= '<h1>' . $key . '</h1>' . $output->chewed($value) . '<br><br>';
    }


    echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
                <title>Example</title>
                <style>
                    @font-face {
                        font-family: "KawkabMono-Regular";
                                src: url("/sources/fonts/KawkabMono-Regular.woff2") format("woff2"),
                                     url("/sources/fonts/KawkabMono-Regular.woff") format("woff");
                    }
                    @font-face {
                        font-family: "Fira Code";
                                src: url("/sources/fonts/FiraCode-Regular.woff") format("woff");
                    }
                    body, span {
                        font-family: "Fira Code", monospace !important;
                        font-size: 12px;
                        line-height: 1.4;
                    }
                </style>
            </head>
            <body>
                <div>' . $result . '</div>
            </body>
        </html>';
}