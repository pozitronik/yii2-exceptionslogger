<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use Exception;
use Throwable;

/**
 * Class LoggedException
 * @package pozitronik\sys_exceptions\models
 * @deprecated @since 1.0.4
 */
class LoggedException extends Exception {

	/**
	 * LoggedException constructor.
	 * @param Throwable $t
	 * @throws Throwable
	 */
	public function __construct(Throwable $t) {
		SysExceptions::log($t, true);
		parent::__construct();
	}

}
