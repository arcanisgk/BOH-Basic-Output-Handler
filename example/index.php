<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BOH - Data Output Manager in PHP Development Environments</title>
    <link rel="stylesheet" href="../sources/bs5/bootstrap.min.css">
</head>
<body>
<div style="margin: 25px">
    <h3 style="color:darkblue">BOH - Data Output Manager in PHP Development Environments - Test and Example Implementations</h3>
    <pre>
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
    </pre>
    <div style="color:orangered;font-weight: bold; float: left; margin-right: 10px">Plain Text Test</div>
    <a href="test/test_plain.php" target="_blank">Link</a> <a href="doc" target="_blank">Doc</a><br>
    <span>
        <b>Note:</b>
        The output contains plain text that is returned from the method.<br>
        This generates a basic plain text, implement it if you need to create a variable<br> usage record in scenario like "CRON" or wherever you require it.
    </span><br><br>
    <div style="color:orangered;font-weight: bold; float: left; margin-right: 10px">Partial Web Environment Test</div>
    <a href="test/test_web_partial.php" target="_blank">Link</a> <a href="doc" target="_blank">Doc</a><br>
    <span>
        <b>Note:</b>
        The output contains the partial HTML code (does not include headers).<br>
        This generates a basic HTML if you have implemented CSS or CSS Framework,<br> it can affect the visibility of the exposed data.
    </span><br><br>
    <div style="color:orangered;font-weight: bold; float: left; margin-right: 10px">Full Web Environment Test</div>
    <a href="test/test_web_full_1.php" target="_blank">Link</a> <a href="doc" target="_blank">Doc</a><br>
    <span>
        <b>Note:</b>
        The output contains the full HTML code, includes headers but is NOT affected by any CSS framework.<br>
        The default HTML code is embedded in an iframe and can be viewed directly.<br>
        This version is recommended if you are implementing a website in which the navigation refreshes the browser window.
    </span><br><br>
    <div style="color:orangered;font-weight: bold; float: left; margin-right: 10px">Full Web Environment Test with Code Injection</div>
    <a href="test/test_web_full_2.php" target="_blank">Link</a> <a href="doc" target="_blank">Doc</a><br>
    <div style="color:orangered;font-weight: bold; float: left; margin-right: 10px">Full Web Environment Test with Code Injection (different CSS / Recommended)</div>
    <a href="test/test_web_full_3.php" target="_blank">Link</a> <a href="doc" target="_blank">Doc</a><br>
    <div style="color:orangered;font-weight: bold; float: left; margin-right: 10px">json Data Return Test</div>
    <a href="test/test_json.php" target="_blank">Link</a> <a href="doc" target="_blank">Doc</a><br>
</div>
</body>

</html>