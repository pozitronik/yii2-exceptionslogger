<?php

declare(strict_types = 1);

namespace Tests\Support;

use Codeception\Actor;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\controllers\MigrateController;
use yii\console\Exception;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class UnitTester extends Actor {
	use _generated\UnitTesterActions;

	private static $_isExecuted = false;

	/**
	 * @param bool $once If true, migrations will be called just once for each tests execution
	 * @return void
	 * @throws Exception
	 * @throws InvalidRouteException
	 */
	public function migrate(bool $once = false):void {
		if ($once && static::$_isExecuted) return;
		$migrationController = new MigrateController('migrations', Yii::$app);
		$migrationController->migrationPath = ['./migrations'];
		$migrationController->interactive = false;
		$migrationController->runAction('up');
		static::$_isExecuted = true;
	}
}
