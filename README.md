ExceptionsLogger
==================
Exceptions logging extension for YII2

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

Yii2,
PHP >= 7.2.0

Usage
-----
At first, run a included migration:

```
yii migrate/up --migrationPath=vendor/pozitronik/yii2-exceptionslogger/migrations
```

This extension provides an SysExceptions::log() static function, that can accept any Throwable interface as first parameter. All exception data will be saved in `sys_exceptions` table (in case of database failure data will be written into `runtime/exception.log` file).

Example of usage
----------------
```php
try {
	$i = $i/0;
} catch (Throwable $t) {
	SysExceptions::log($t);//just silently log exception
	SysExceptions::log(new RuntimeException("Someone tried divide to zero"), false, true);//silently log own exception and mark it as known error
	SysExceptions::log(new RuntimeException("It prohibited by mathematics"), true);//log own exception and throw it
}
```