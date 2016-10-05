# Codeception DataProvider Module

[![Build Status](https://travis-ci.org/jacekk/codeception-dataprovider-module.svg)](https://travis-ci.org/jacekk/codeception-dataprovider-module)
[![Total Downloads](https://poser.pugx.org/jacekk/codeception-dataprovider-module/d/total.svg)](https://packagist.org/packages/jacekk/codeception-dataprovider-module)
[![Latest Stable Version](https://poser.pugx.org/jacekk/codeception-dataprovider-module/v/stable.svg)](https://packagist.org/packages/jacekk/codeception-dataprovider-module)
[![Latest Unstable Version](https://poser.pugx.org/jacekk/codeception-dataprovider-module/v/unstable.svg)](https://packagist.org/packages/jacekk/codeception-dataprovider-module)
[![License](https://poser.pugx.org/jacekk/codeception-dataprovider-module/license.svg)](https://packagist.org/packages/jacekk/codeception-dataprovider-module)

This module helps to manage data (that you use inside tests) in an easy way. Especially, when you like Yaml/YML files format.

* [Installation](#installation)
* [Parameters](#parameters)
* [Usage examples](#usage-examples)
* [License](#license)

## Installation

Add the package into your *composer.json* file:

```json
{
    "require-dev": {
        "codeception/codeception": "2.*",
        "jacekk/jacekk/codeception-dataprovider-module": "1.*"
    }
}
```

Tell Composer to download the package:

```
$ composer update
```

Then, enable it in your `my-awesome.suite.yml` configuration and adjust two required params as in [this library suite](test/tests/acceptance.suite.yml) config:

```yaml
class_name: NoGuy
modules:
    enabled:
        - Asserts
        - DataProvider
    config:
        DataProvider:
            dataPathTpl: '{root}/tests/_data/{file}'
            files:
                - common-provider.yml
                - env-provider.dev.yml
```

You will need to rebuild your actor class:

```
$ php codecept.phar build
```

or

```sh
$ vendor/bin/codecept build
```

or whatever your tool set allowes :) At last, check out the [examples](#usage-examples) section and use it daily :)

## Parameters

#### dataPathTpl

Template to build paths to files listed by appropriate setting. Allowes for the following tokens, where the first one is obviously required:

* ```{file}``` - one of elements listed in **files** setting,
* ```{root}``` - current working directory - PHP function: `getcwd()`.

#### files

One or more files names, which can be found under path defined in **dataPathTpl**.
If `--env` param is used heavily, then some *files* reuse is sort of *built in*.

## Usage examples

#### getValue($keyName, $default = null)

YML content:

```yaml
headers:
    contentType: 'application/json'
    accept: 'text/html'
```

PHP code:

```php
public function testSomeHeaders(NoGuy $I)
{
    $headerValue = $I->getValue('headers.accept');
    $I->assertEquals('text/html', $headerValue);
    // or with somethin not set in YML files
    $authType = $I->getValue('headers.authorizationType', 'Bearer');
    $I->assertEquals('Bearer', $authType);
}
```

#### iterateOver($keyName, callable $callback)

YML content:

```yaml
users:
    admins:
        0:
            id: 123
            email: John(at)example.com
            fullName: John Example
        1:
            id: 321
            email: two(at)gmail.com
            fullName: Tom The Second
```

or (this will also work, even it is not an ordered list):
```yaml
users:
    admins:
        -
            id: 111
            email: mark(at)gmail.com
            fullName: Mark Whaleberg
```

PHP code:

```php
public function testAdminsDataInUsersResource(NoGuy $I)
{
    $I->iterateOver('users.admins', function ($user, $index) use ($I) {
        $userId = $user['id'];
        $I->sendGET("users/{$userId}");
        $I->seeResponseContainsJson([
            'id'        => $userId,
            'is_admin'  => true,
            'email'     => $user['email'],
            'full_name' => $user['fullName'],
        ]);
    });
}
```

See more examples in [GetValueCest](test/tests/acceptance/GetValueCest.php) and [IterateOverCest](test/tests/acceptance/IterateOverCest.php) which verify this module quality.

## License

Released under the same licence as Codeception: MIT.
