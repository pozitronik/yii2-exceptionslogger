<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Codeception\Test\Unit;
use pozitronik\sys_exceptions\models\SysExceptions;
use RuntimeException;
use Tests\Support\UnitTester;
use Throwable;

/**
 * Tests different user identifiers type saving
 */
class UserIdentifiersTest extends Unit {

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
	 */
	public function testEmptyUserIdentifiers():void {
		SysExceptions::log(new RuntimeException('Someone tried divide to zero'), false, true);//silently log own exception and mark it as known error

		/** @var SysExceptions $exception */
		$exception = SysExceptions::find()->orderBy(['id' => SORT_DESC])->one();
		static::assertEquals(null, $exception->user_id);
	}

}
