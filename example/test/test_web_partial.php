<!--

* BOH - Data Output Manager in PHP Development Environments.
* PHP Version required 7.4.* or higher
* This example shows how the BOH class and its function/methods are declared.
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

-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partial Web Environment Test</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <div class="helper">
        <h3>Partial Web Environment Test.</h3>
        <span>
            <b>Note:</b>
            The output contains the partial HTML code (does not include headers).<br>
            This generates a basic HTML if you have implemented CSS or CSS Framework,<br>
            it can affect the visibility of the exposed data.<br>
        </span>
        <?php
        $fileContent = file_get_contents('../test/' . basename(__FILE__));
        $render      = highlight_string($fileContent, true);

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
             * implement function to output and expose a variable data.
             */

            /**
             * Option Used, See Documentation for a deep explanation.
             * @var $object_casted
             */

            setOptionsChewData(['indent' => false, 'debug' => true]);

            echo chewData($object_casted);

            /**
             * Option Used, See Documentation for a deep explanation.
             * with Indent and Different color scheme
             * @var $object_casted
             */

            setOptionsChewData(['indent' => true, 'theme' => 'monokai', 'debug' => true]);

            echo chewData($object_casted);

            /**
             * Option Used, See Documentation for a deep explanation.
             * with Indent and Different color scheme
             * @var $object_casted
             */

            setOptionsChewData(['indent' => true, 'theme' => 'red', 'debug' => true]);

            echo chewData($class_trait_instance);

            /**
             * Option Used, See Documentation for a deep explanation.
             * with Indent and Different color scheme
             * @var $object_casted
             */

            setOptionsChewData(['indent' => true, 'theme' => 'vscode', 'debug' => true]);

            echo chewData($object_casted);

        }

        /** Implementation End */
        /*====================================================================*/

        ?>
    </div>
    <div class="helper">
        <h3>Help for Implementation.</h3>
        <?php
        echo $render;
        ?>
    </div>
</div>
</body>
</html>