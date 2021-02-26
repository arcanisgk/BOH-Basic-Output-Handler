# [BOH] Basic Output Handler for PHP

[![GitHub license](https://img.shields.io/github/license/arcanisgk/BOH-Basic-Ouput-Handler)](https://github.com/arcanisgk/BOH-Basic-Ouput-Handler/blob/main/LICENSE)
[![State](https://img.shields.io/static/v1?label=release&message=v1.0.4&color=blue 'Latest known version')](https://github.com/arcanisgk/BOH-Basic-Ouput-Handler/tree/v1.0.4) <!-- __SEMANTIC_VERSION_LINE__ -->
[![GitHub issues](https://img.shields.io/github/issues/arcanisgk/BOH-Basic-Ouput-Handler)](https://github.com/arcanisgk/BOH-Basic-Ouput-Handler/issues)
[![Minimum PHP version](https://img.shields.io/static/v1?label=PHP&message=7.4.0+or+higher&color=blue "Minimum PHP version")](https://www.php.net/releases/7_4_0.php)

Acronym: [BOH].

Name: Basic Output Handler.

Dependencies: Stand Alone / PHP v7.4.

## What does *[BOH]* do?

*[BOH]* is a very simple PHP [output handler] implementation that show Human readable information instead of using the default PHP options:

- var_dump() - Displays information about a variable.
- print_r() - Print human-readable information about a variable.
- debug_zval_dump() - Outputs a string representing an internal value of zend.
- var_export() - Print or return a string representation of a parseable variable.

This means that all the data passed is presented to the developer according to the chosen parameters. It also means that the displayed data can be directly reused as code. Comments are also generated for each value that briefly explains the type of data

## Why use *[BOH]*?

Developers need the ability to decide how their code behaves when data needs to be checked. The native php Methods provide a range of information that is not reusable by the Developer or may even require more work to get the correct output for data verification.

This library handles data output proven to be extremely effective. *[BOH]* is a
Standalone implementation that can be used for any project and does not require a third-party library or software.

## Help to improve *[BOH]*?

if you want to collaborate with the development of the library; You can express your ideas or report any situation related to this in:
https://github.com/arcanisgk/BOH-Basic-Ouput-Handler/issues

## *[BOH]* Configuration:
None necessary.

## *[BOH]* Installation:

```cmd
composer require arcanisgk/boh-basic-ouput-handler
or 
composer require arcanisgk/boh-basic-ouput-handler --dev
```

## *[BOH]* Usage:

### Instance of Class
```php

use \IcarosNet\BOHBasicOuputHandler as Output;
require __DIR__.'\..\vendor\autoload.php';
$output = new Output\Output_Handler();
```

### Customiced colors palette:

```php

//you can define the theme to be used in the output in web or cli:
$output = new Output\Output_Handler('monokai'); //in the class Instance or
$output->Theme('monokai');                      //call the method Theme
```


### Options for Method:
```php

$output->output($example_array);            // send the variable and library would check the best output for you.
$output->output($example_array,'web');      // or send a second argument for enviroment 'web' or 'cli'.
$output->output($example_array,'web',true); // if you need retrive the string instead of output send true in thirt param.
$output->output($example_array,'cli');      // the library support output for CLI enviroment.
$output->output($example_array,'cli',true); // an cli can retrive strings.
```

## Example output:

![Image of Example Output ](https://i.imgur.com/5WQ1Dd4.jpg)

## Unit Test for output:

Unit test Pending for version v1.1.0

### Contributors
- (c) 2020 - 2021 Walter Francisco Núñez Cruz icarosnet@gmail.com [![Donate](https://img.shields.io/static/v1?label=Donate&message=PayPal.me/wnunez86&color=brightgreen)](https://www.paypal.me/wnunez86/4.99USD)

### I want to give special credit to the Help, Collaboration and Support of the programmers fron php.net, StackExchange Community and Reddit(/r/PHPhelp/):

- Mr_What4 (Reddit)
- CyberJack77 (Reddit)
- requinix (PHP.net)
- patricio-moracho (SOes)
- quevedo (SOes)
- mauricio-contreras (SOes)
- gbianchi (SOes)
- Triby (SOes)
- Marcos (SOes)
- anythingg (SOes)
- mdfst13 (CodeReview SE)
- Sᴀᴍ Onᴇᴌᴀ (CodeReview SE)
- mast (CodeReview SE)
- der-kommissar (CodeReview SE)
- peilonrayz (CodeReview SE)

### Those who have supported me in this project in an invaluable way, Greetings Guys.