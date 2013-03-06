# Constant Contact PHP SDK

## Installation

### Manual Installation
1. Download and extract the project into an appropriate place in your application.
2. Require the libraries own autoloader. (note: the path to include the autoload may be different depending on your applications structure)
```
require '/src/Ctct/autoload.php'
```

### Composer
Composer is a dependency management tool for PHP that allows you to declare the dependencies your project needs and installs them into your project. In order to use the Constant Contact PHP SDK through composer, you must do the following 

1. Add "ctct/ctct-sdk-php" as a dependency in your project's composer.json file.
```javascript
 {
        "require": {
            "ctct/ctct-sdk-php": "1.0"
        }
    }
```

2. Downoad and install Composer.
```
curl -s "http://getcomposer.org/installer" | php
```

3. Install your dependencies by executing the following in your project root.
```
php composer.phar install
```

4. Require Composer's autoloader.
Composer also prepared an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process.
```
require 'vendor/autoload.php';
```

## Usage
Once the autoloader has been required, you can now starting using the SDK.
```php
use Ctct/ConstantContact;
$cc = new ConstantContact('your api key');

$contacts = $cc->getContacts('your access token')
```