<?php
declare(strict_types = 1);

namespace Tests\Functional;

use app\models\UsersFaker;
use Codeception\Exception\ModuleException;
use pozitronik\sys_exceptions\models\SysExceptions;
use RuntimeException;
use Tests\Support\FunctionalTester;
use Throwable;
use Yii;
use yii\base\Exception as BaseException;
use yii\base\InvalidRouteException;
use yii\console\Exception;

/**
 * Tests different user identifiers type saving
 */
class UserIdentifiersTestCest {
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
	 * @throws ModuleException
	 * @throws Throwable
	 */
	public function testIntegerUserIdentifiers(FunctionalTester $I):void {
		$user = UsersFaker::findIdentity(1);
		$I->amLoggedInAs($user);
		$I->assertEquals($user->id, Yii::$app->user->id);
		SysExceptions::log(new RuntimeException('Someone tried divide to zero'), false, true);//silently log own exception and mark it as known error

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		$I->assertEquals($user->id, $exception->user_id);
	}

	/**
	 * @param FunctionalTester $I
	 * @return void
	 * @throws ModuleException
	 * @throws Throwable
	 * @throws BaseException
	 */
	public function testUUIDUserIdentifiers(FunctionalTester $I):void {
		$uuid = uniqid('', true);
		$user = UsersFaker::findIdentity($uuid);
		$I->amLoggedInAs($user);
		$I->assertEquals($user->id, Yii::$app->user->id);
		SysExceptions::log(new RuntimeException('Someone tried divide to zero'), false, true);//silently log own exception and mark it as known error

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		$I->assertEquals($uuid, $exception->user_id);
	}
}
