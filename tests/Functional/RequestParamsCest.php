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
	 * @covers ErrorHandler
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
		$I->assertEquals(37, $exception->line);
		$I->assertEquals("I'm a teapot", $exception->message);
		$I->assertEquals(json_encode([]), $exception->get);
		$I->assertEquals(json_encode([]), $exception->post);
		$I->assertFalse($exception->known);
	}

	/**
	 * @param FunctionalTester $I
	 * @return void
	 * @throws InvalidConfigException
	 */
	public function testGetAndPost(FunctionalTester $I):void {
		$post = [
			'postParamString' => 'postParamStringValue',
			'postParamInt' => 174,
			'postParamBool' => false,
			'postParamArray' => ['some command', 10, true, 56]
		];
		$postAsStrings = [//because POSTs are always strings
			'postParamString' => 'postParamStringValue',
			'postParamInt' => '174',
			'postParamBool' => '',
			'postParamArray' => ['some command', '10', '1', '56']
		];

		Yii::$app->set('errorHandler', [
			'class' => ErrorHandler::class,
			'errorAction' => 'site/error'
		]);
		$I->wantTo('Check that GET and POST params are logged');
		$I->sendPost('site/fail?getParam=getParamValue', $post);

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		$I->assertEquals(0, $exception->user_id);
		$I->assertEquals(0, $exception->code);

		$I->assertStringEndsWith('SiteController.php', $exception->file);//the test file path can be different in different environments
		$I->assertEquals(37, $exception->line);
		$I->assertEquals("I'm a teapot", $exception->message);
		$I->assertEquals(json_encode(['getParam' => 'getParamValue']), $exception->get);
		$I->assertEquals(json_encode($postAsStrings), $exception->post);
		$I->assertFalse($exception->known);
	}
}
