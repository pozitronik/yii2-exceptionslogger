<?php
declare(strict_types = 1);

use Codeception\Test\Unit;
use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\sys_exceptions\SysExceptionsModule;
use Tests\Support\UnitTester;
use yii\base\InvalidConfigException;

/**
 * @covers SysExceptions::getCustomData()
 */
class CustomDataTest extends Unit {

	protected UnitTester $tester;

	/**
	 * @inheritDoc
	 */
	protected function _before():void {
		$this->tester->migrate();
	}

	/**
	 * @return void
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function testCustomDataRetrieval():void {
		$this->tester->wantToTest("How is custom data retrieval method works");
		Yii::$app->setModule('SysExceptionsModule', [
			'class' => SysExceptionsModule::class,
			'params' => [
				'customDataHandler' => fn():string => uniqid('uniq', true)
			]
		]);
		SysExceptions::log(new RuntimeException("First exception with custom data"));
		/** @var SysExceptions $exception1 */
		$exception1 = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		static::assertEquals("First exception with custom data", $exception1->message);
		static::assertNotNull($exception1->custom_data);
		static::assertStringStartsWith('uniq', $exception1->custom_data);

		SysExceptions::log(new RuntimeException("Second exception with custom data"));
		/** @var SysExceptions $exception2 */
		$exception2 = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		static::assertEquals("Second exception with custom data", $exception2->message);
		static::assertNotNull($exception2->custom_data);
		static::assertStringStartsWith('uniq', $exception2->custom_data);

		static::assertNotEquals($exception1->custom_data, $exception2->custom_data);
	}

	/**
	 * @return void
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function testCustomDataJson():void {
		$this->tester->wantToTest("JSON store in custom_data field");
		Yii::$app->setModule('SysExceptionsModule', [
			'class' => SysExceptionsModule::class,
			'params' => [
				'customDataHandler' => fn():string => json_encode(['a' => uniqid('json', true)])
			]
		]);
		SysExceptions::log(new RuntimeException("Exception with custom JSON data"));
		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		static::assertEquals("Exception with custom JSON data", $exception->message);
		static::assertNotNull($exception->custom_data);
		static::assertJson($exception->custom_data);
	}

}
