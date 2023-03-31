<?php
declare(strict_types = 1);

namespace Tests\Functional;

use pozitronik\sys_exceptions\models\ErrorHandler;
use pozitronik\sys_exceptions\models\SysExceptions;
use Tests\Support\FunctionalTester;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\console\Exception;

/**
 *
 */
class RequestParamsCest {
	/**
	 * @param FunctionalTester $I
	 * @return void
	 * @throws InvalidRouteException
	 * @throws Exception
	 */
	public function _before(FunctionalTester $I):void {
		$I->migrate();
	}

	/**
	 * @param FunctionalTester $I
	 * @return void
	 * @throws InvalidConfigException
	 * @covers \pozitronik\sys_exceptions\models\ErrorHandler
	 */
	public function justFail(FunctionalTester $I):void {
		Yii::$app->set('errorHandler', [
			'class' => ErrorHandler::class,
			'errorAction' => 'site/error'
		]);

		$I->amOnPage('site/fail');

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		$I->assertEquals(0, $exception->user_id);
		$I->assertEquals(0, $exception->code);

		$I->assertStringEndsWith('SiteController.php', $exception->file);//the test file path can be different in different environments
		$I->assertEquals(35, $exception->line);
		$I->assertEquals("I'm a teapot", $exception->message);
		$I->assertEquals(json_encode([]), $exception->get);
		$I->assertEquals(json_encode([]), $exception->post);
		$I->assertFalse($exception->known);
	}
}
