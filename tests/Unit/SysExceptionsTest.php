<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Codeception\Test\Unit;
use pozitronik\sys_exceptions\models\SysExceptions;
use RuntimeException;
use Tests\Support\UnitTester;
use Throwable;
use Yii;
use yii\web\Application;

/**
 *
 */
class SysExceptionsTest extends Unit {

	protected UnitTester $tester;

	/**
	 * @inheritDoc
	 */
	protected function _before():void {
		$this->tester->migrate();
	}

	/**
	 * @return void
	 */
	public function testSomeFeature():void {
		$this->tester->assertInstanceOf(Application::class, Yii::$app);
	}

	/**
	 * @return void
	 * @throws Throwable
	 */
	public function testLoggerSilently():void {
		$i = 1;
		try {
			$i /= 0;//this error must be on the line 42
		} catch (Throwable $t) {
			SysExceptions::log($t);//just silently log exception
		}

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		static::assertEquals(0, $exception->user_id);
		static::assertEquals(0, $exception->code);

		static::assertEquals(__FILE__, $exception->file);//the test file path can be different in different environments
		static::assertEquals(42, $exception->line);
		static::assertEquals('Division by zero', $exception->message);
		static::assertEquals(json_encode([]), $exception->get);
		static::assertEquals(json_encode([]), $exception->post);
		static::assertFalse($exception->known);

	}

	/**
	 * @return void
	 * @throws Throwable
	 */
	public function testLoggerSilentlyKnown():void {
		SysExceptions::log(new RuntimeException("Someone tried divide to zero"), false, true);//silently log own exception and mark it as known error

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		static::assertEquals(0, $exception->user_id);
		static::assertEquals(0, $exception->code);

		static::assertEquals(__FILE__, $exception->file);//the test file path can be different in different environments
		static::assertEquals(66, $exception->line);
		static::assertEquals('Someone tried divide to zero', $exception->message);
		static::assertEquals(json_encode([]), $exception->get);
		static::assertEquals(json_encode([]), $exception->post);
		static::assertTrue($exception->known);

	}

	/**
	 * @return void
	 */
	public function testWrapper():void {

	}

	/**
	 * @return void
	 */
	public function testHandler():void {

	}
}
