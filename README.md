# Constant Contact PHP SDK
[![Build Status](https://secure.travis-ci.org/constantcontact/php-sdk.png?branch=master)](http://travis-ci.org/constantcontact/php-sdk) [![Latest Stable Version](https://poser.pugx.org/constantcontact/constantcontact/v/stable.svg)](https://packagist.org/packages/constantcontact/constantcontact) [![Latest Unstable Version](https://poser.pugx.org/constantcontact/constantcontact/v/unstable.svg)](https://packagist.org/packages/constantcontact/constantcontact)

### This library utilizes [GuzzlePHP](http://guzzle.readthedocs.org/)

## Installing via Composer
[Composer](https://getcomposer.org/) is a dependency management tool for PHP that allows you to declare the dependencies your project needs and installs them into your project. In order to use the Constant Contact PHP SDK through composer, you must add "constantcontact/constantcontact" as a dependency in your project's composer.json file.
```javascript
 {
        "require": {
            "constantcontact/constantcontact": "2.1.*"
        }
    }
```

Or, if you would like a more bleeding edge build, which has features like the newest version of GuzzlePHP and a minimum of PHP 5.5, you can build off our development branch.

```javascript
 {
        "require": {
            "constantcontact/constantcontact": "dev-development"
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
use Ctct\ConstantContact;
$cc = new ConstantContact('your api key');

$contacts = $cc->contactService->getContacts('your access token')
```

Many methods will take an array of parameters for use in the calls. Available params are documented in the PHPDoc of the method.
```php
$params = array("limit" => 500);
$contacts = $cc->contactService->getContacts('your access token', $params);
```
## Minimum Requirements
Use of this library requires PHP 5.4+

If you are being required to use an older version of PHP, it is highly recommended that you update to at least 5.4 - but you can use version 1.3.* (PHP 5.3+) via composer, or [manually](https://github.com/constantcontact/php-sdk/releases) (but note that versions 2 and up require other dependencies).
