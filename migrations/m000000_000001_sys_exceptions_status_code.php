<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m000000_000001_sys_exceptions_status_code
 */
class m000000_000001_sys_exceptions_status_code extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_exceptions', 'statusCode', $this->integer()->null()->after('code')->comment('HTTP status code'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_exceptions', 'status_code');
	}

}
