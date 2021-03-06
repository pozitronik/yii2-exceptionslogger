<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use yii\web\ErrorHandler as YiiErrorHandler;

/**
 * Class ErrorHandler
 */
class ErrorHandler extends YiiErrorHandler {

	/**
	 * @inheritDoc
	 */
	public function logException($exception):void {
		parent::logException($exception);
		SysExceptions::log($exception);
	}
}