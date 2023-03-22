<?php /** @noinspection UsingInclusionReturnValueInspection */
declare(strict_types = 1);
use yii\caching\DummyCache;

$config = [
	'id' => 'basic-console',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'app\commands',
	'aliases' => [
		'@vendor' => './vendor',
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
		'@tests' => '@app/tests',
		'@cusodede' => '@vendor/cusodede'
	],
	'components' => [
		'cache' => [
			'class' => DummyCache::class,
		],
	],
	'params' => [],
];

return $config;
