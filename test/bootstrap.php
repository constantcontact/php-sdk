<?php
// autoloader for the SDK
require_once(realpath(dirname(__FILE__) . '/../') . "/src/Ctct/autoload.php");

// load the JsonLoader
require_once(__DIR__ . "/Json/JsonLoader.php");

// autoload composer dependencies
if (!@include __DIR__ . '/../vendor/autoload.php') {
    die('You must set up the project dependencies, run the following commands:
        wget http://getcomposer.org/composer.phar
        php composer.phar install');
}
