<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use yii\web\ErrorHandler as YiiErrorHandler;

/**
 * Class ErrorHandler
 */
class ErrorHandler extends YiiErrorHandler {

	/**
	 * @var int|null id of last logged exception
	 */
	public null|int $id = null;

	/**
	 * @inheritDoc
	 */
	public function logException($exception):void {
		parent::logException($exception);
		$this->id = SysExceptions::log($exception);
	}
}