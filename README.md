ExceptionsLogger
==================
Exceptions logging extension for YII2

[![Build Status](https://github.com/pozitronik/yii2-exceptionslogger/actions/workflows/ci.yml/badge.svg)](https://github.com/pozitronik/yii2-exceptionslogger/actions)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Run

```
php composer.phar require pozitronik/yii2-exceptionslogger "dev-master"
```

or add

```
"pozitronik/yii2-exceptionslogger": "dev-master"
```

to the require section of your `composer.json` file.

Requirements
------------

Yii2, PHP >= 8.0

Configuration
-------------

Run a included migration:

```
yii migrate/up --migrationPath=vendor/pozitronik/yii2-exceptionslogger/migrations
```

It creates the `sys_exceptions` table, which will store exceptions data.

Usage
-----

## Direct logger

This extension provides the `SysExceptions::log()` function, that can accept any `Throwable` interface as its first parameter. The exception
data will be saved in `sys_exceptions` table (in case of failure, the data will be written into `runtime/exception.log` file).

Example:

```php
try {
	$i = $i/0;
} catch (Throwable $t) {
	SysExceptions::log($t);//just silently log exception
	SysExceptions::log(new RuntimeException("Someone tried divide to zero"), false, true);//silently log own exception and mark it as known error
	SysExceptions::log(new RuntimeException("It prohibited by mathematics"), true);//log own exception and throw it
}
```

## Default exceptions handler

You can use the `pozitronik\sys_exceptions\models\ErrorHandler` class as default application error handler to log all exceptions
automatically. Define it in your application config, like:

```php

$config = [
    'components' => [
        'errorHandler' => [
            'class' => pozitronik\sys_exceptions\models\ErrorHandler::class,
            'errorAction' => 'site/error'
        ]
    ];
```

# How to add custom data to a log record?

It is possible to add custom string data (e.g. some trace identifier) to every logged error. Configure the `customDataHandler` parameter for
the `SysExceptionsModule` module:

```php
$config = [
        'modules' => [
            'SysExceptionsModule' => [
                'params' => [
                    'customDataHandler' => static function():string {
                        return Yii::$app->request?->csrfToken;//for example
                    }
                ]
            ],
        ]
    ]
```

The data will be stored in the `custom_data` text field.

License
-------

GNU GPL v3.0