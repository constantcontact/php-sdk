<?php
// autoloader for the SDK
require_once(realpath(dirname(__FILE__) . '/../') . "/src/Ctct/autoload.php");

// load the JsonLoader
require_once(__DIR__ . "/Json/JsonLoader.php");

// autoload composer dependencies
require_once(realpath(dirname(__FILE__) . '/../') . "/vendor/autoload.php");
