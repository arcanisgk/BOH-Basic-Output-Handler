<!--

* BOH - Data Output Manager in PHP Development Environments.
* PHP Version required 7.4.* or higher
*
* @see https://github.com/IcarosNetSA/BOH-Basic-Output-Handler
*
* @author    Walter Nuñez (arcanisgk/original founder)
* @email     icarosnet@gmail.com
* @copyright 2020 - 2021 Walter Nuñez.
* @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
* @note      This program is distributed in the hope that it will be useful
*            WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
*            or FITNESS FOR A PARTICULAR PURPOSE.
*
* This example shows how the BOH class and its function/methods are declared.

-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test of Plain Output</title>
    <link rel="stylesheet" href="../sources/bs5/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <div class="helper">
        <h3>Test of Plain Output.</h3>
        <span>
            <b>Note:</b>
            The output contains plain text that is returned from the method.<br>
            This generates a basic plain text, implement it if you need to create<br>
            a variable usage record in scenario like "CRON" or wherever you require it.<br><br>
        </span>
        <?php

        /*====================================================================*/
        /** Implementation Starts */

        /**
         * This example shows how the BOH class and its methods are declared.
         * this Implementation use composer autoloader, ignore if you have it working
         */

        const PATH          = __DIR__ . '\..\..\vendor\autoload.php';
        const TEST_VARIABLE = __DIR__ . '\..\test_data\test_variable.php';

        /**
         * Validate the existence of the composer Autoload to Import.
         */

        if (file_exists(PATH)) {

            /**
             * import the class [BOH] to be used as an example.
             */

            require_once PATH;
        } else {
            echo 'This library "[BOH] Basic OutputHandler Handler for PHP" requires 
        composer installation and autoload; run composer install command in your root.';
            die;
        }

        /**
         * Validate the existence of the File test_variable.
         */

        if (!file_exists(TEST_VARIABLE)) {
            echo '"[BOH] Basic OutputHandler Handler for PHP" need of test_variable.php 
        file to perform test output.';
            die;
        } else {

            require_once TEST_VARIABLE;

            /**
             * implement function to put the output in a file.
             */

            function filePutData($string)
            {
                $file = '..\outfile\data.html';
                file_put_contents(
                    $file,
                    '<pre>' . PHP_EOL . $string . PHP_EOL . '</pre>',
                    LOCK_EX);
                echo '<a href="' . $file . '" target="_blank"> link view output </a>';
            }

            /**
             * Option Used, See Documentation for a deep explanation.
             * @var $example_big_array
             */

            setOptionsChewData(['indent' => true, 'return' => true, 'env' => 'plain', 'debug' => true]);

            /**
             * Usage of chewData
             * @var $example_big_array
             */

            $output = chewData($example_big_array);

            /**
             * Usage of filePutData to fill the file
             * @var $example_big_array
             */

            filePutData($output);

        }

        /** Implementation End */
        /*====================================================================*/

        ?>
    </div>
    <div class="helper">
        <h3>Help for Implementation.</h3>
        <?php
        $fileContent = file_get_contents('../test/' . basename(__FILE__));
        echo highlight_string($fileContent, true)
        ?>
    </div>
</div>
</body>
</html>