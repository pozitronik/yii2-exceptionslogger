<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Codeception\Test\Unit;
use DivisionByZeroError;
use pozitronik\sys_exceptions\models\ErrorHandler;
use pozitronik\sys_exceptions\models\SysExceptions;
use Tests\Support\UnitTester;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;

/**
 * @covers \pozitronik\sys_exceptions\models\ErrorHandler
 */
class HandlerTest extends Unit {

	protected UnitTester $tester;

	/**
	 * @inheritDoc
	 */
	protected function _before():void {
//		$this->tester->migrate();
	}

	/**
	 * @return void
	 * @throws InvalidConfigException
	 * @skip This test won't be executed correctly, because Codeception set his own error handler
	 * I left this code here as example.
	 */
	public function testWrapper():void {
		Yii::$app->set('errorHandler', [
			'class' => ErrorHandler::class,
			'errorAction' => 'site/error'
		]);

		static::assertInstanceOf(ErrorHandler::class, Yii::$app->errorHandler);

		$this->expectException(DivisionByZeroError::class);
		try {
			/** @noinspection PhpDivisionByZeroInspection */
			/** @noinspection PhpUnusedLocalVariableInspection */
			$i = 100/0;
		} catch (Throwable $t) {

		}

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		static::assertEquals(0, $exception->user_id);
		static::assertEquals(0, $exception->code);

		static::assertEquals(__FILE__, $exception->file);//the test file path can be different in different environments
		static::assertEquals(40, $exception->line);
		static::assertEquals('Division by zero', $exception->message);
		static::assertEquals(json_encode([]), $exception->get);
		static::assertEquals(json_encode([]), $exception->post);
		static::assertFalse($exception->known);
	}
}
