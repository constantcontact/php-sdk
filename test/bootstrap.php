<?php
/**
 * This bootstrap file is meant to be include all of the files required to run PHPUnit testing
 */

// autoloader for the SDK
require_once(realpath(dirname(__FILE__) . '/../') . "/src/Ctct/autoload.php");

// load the JsonLoader 
require_once(__DIR__ . "/Json/JsonLoader.php");