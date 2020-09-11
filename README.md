# Constant Contact PHP SDK

### This library utilizes [GuzzlePHP](http://guzzle.readthedocs.org/)

## Installing via Composer (recommended)

[Composer](https://getcomposer.org/) is a dependency management tool for PHP that allows you to declare the dependencies your project needs and installs them into your project. In order to use the Constant Contact PHP SDK through composer, you must add "yousaf-saqib/cc-php-sdk" as a dependency in your project's composer.json file which is a fork of "constantcontact/constantcontact".
```javascript
 {
        "require": {
            "yousaf-saqib/cc-php-sdk": "1.0.*"
        }
    }
```

### Manual Installation
If you are unable to install using composer, we have provided a zip file that includes a version of the dependencies at the time of our release, as well as our library. Unzip the vendor file in the standalone directory, and require the autoload.php file to use our methods.

## Documentation

The source documentation is hosted at http://constantcontact.github.io/php-sdk

API Documentation is located at http://developer.constantcontact.com/docs/developer-guides/api-documentation-index.html

## Usage
The ConstantContact class contains the underlying services that hold the methods that use the API.
```php
use YousafSaqib\ConstantContact\ConstantContact;
$cc = new ConstantContact('your api key');

$contacts = $cc->contactService->getContacts('your access token')
```

Many methods will take an array of parameters for use in the calls. Available params are documented in the PHPDoc of the method.
```php
$params = array("limit" => 500);
$contacts = $cc->contactService->getContacts('your access token', $params);
```
## Minimum Requirements
Use of this library requires PHP 7.2+, and PHP cURL extension (http://php.net/manual/en/book.curl.php)

If you are being required to use an older version of PHP, it is highly recommended that you update to at least 7.2 .


## Changes from Orignal Client

- New attribute added in connstant contant campaign tracking activity which contains clicked uri information