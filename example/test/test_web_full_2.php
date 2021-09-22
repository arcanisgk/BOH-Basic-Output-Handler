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

const PATH          = __DIR__ . '\..\..\vendor\autoload.php';
const TEST_VARIABLE = __DIR__ . '\..\test_data\test_variable.php';


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
     *      - 'vscode'
     *      - 'red-redemption'
     *      - 'gray-scale'
     *
     * + set if we want to use indentation between name, values and comments.
     * - 'indent'
     *      - true (default)
     *      - false
     */

    //setOptionsChewData(['indent' => true, 'return' => true]);

    /**
     * Use the variables in /example/test_variable.php to get a test view of each type of data that can be analyzed.
     * @var $example_big_array
     * Examples Area:
     */

    /**
     * Test 1: get array to Plain txt
     */

    //filePutData(chewData($example_big_array));

    /**
     * Test 2: output to Html inline
     */

    //setOptionsChewData(['indent' => false, 'env' => 'web', 'theme' => 'gray']);

    /**
     * @var $object
     */
    //chewData($object);
    echo chewData($object_casted);
    //chewData($example_big_array);

    /*
    $c   = 0;
    $out = '';
    foreach ($example_big_array as $key => $value) {
        $c++;
        $out .= '<h3 style="color:orangered"> Example #' . $c . ' of type: ' . $key . '</h3>';
        $out .= chewData($value);
    }
    */


    //echo '<br></div><div style="width:40%; float:right; overflow-x: scroll; white-space: nowrap; height:100%; min-height:100%;"><h1>Help for Implementation.</h1></div>';

    /*
    echo '<style>


                .boh-container {
                  display: flex;
                  align-items: flex-start;
                  justify-content: right;
                  height: 100vh;
                }
                .boh-container a {
                  padding: 5px;
                  background: teal;
                  color: #fff;
                  font-weight: bold;
                  font-size: 14px;
                  border-radius: 3px;
                  cursor: pointer;
                }
                .boh-modal {
                  position: fixed;
                  width: 100vw;
                  height: 100vh;
                  opacity: 0;
                  visibility: hidden;
                  transition: all 0.3s ease;
                  top: 0;
                  left: 0;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                }
                .boh-modal.open {
                  visibility: visible;
                  opacity: 1;
                  transition-delay: 0s;
                }
                .boh-modal-bg {
                  position: absolute;
                  background: teal;
                  width: 100%;
                  height: 100%;
                }
                .boh-modal-container {
                  width: 80vw;
                  height: 80vh;
                  border-radius: 10px;
                  background: #fff;
                  position: relative;
                  padding: 10px;
                }
                .boh-modal-close {
                  position: absolute;
                  right: 15px;
                  top: 15px;
                  outline: none;
                  appearance: none;
                  color: red;
                  background: none;
                  border: 0px;
                  font-weight: bold;
                  cursor: pointer;
                }
        </style>
        <div class="boh-container">
          <a data-modalboh="modal-boh-1">View Output</a>
        </div>
        <div class="boh-modal" id="modal-boh-1">
          <div class="boh-modal-bg boh-modal-exit"></div>
          <div class="boh-modal-container">
            <h3>Output developer Iframe</h3>
            <button class="boh-modal-close boh-modal-exit">X</button>
            <span> ' . $out . '</span>
          </div>
        </div>
        <script>
            let modals = document.querySelectorAll("[data-modalboh]");
            modals.forEach(function(trigger) {
              trigger.addEventListener("click", function(event) {
                event.preventDefault();
                let modal = document.getElementById(trigger.dataset.modalboh);
                modal.classList.add("open");
                let exits = modal.querySelectorAll(".boh-modal-exit");
                exits.forEach(function(exit) {
                  exit.addEventListener("click", function(event) {
                    event.preventDefault();
                    modal.classList.remove("open");
                  });
                });
              });
            });
        </script>
    ';
    */

}

function filePutData($string)
{
    $file = 'data.html';
    file_put_contents($file, '<pre>' . PHP_EOL . $string . PHP_EOL . '</pre>', FILE_APPEND | LOCK_EX);
    echo '<h2 style = "color:green" > --File Output Loaded Like String for File Storage-- </h2 > ';
    echo '<a href = "' . $file . '" target = "_blank" > link view output </a > ';
}
