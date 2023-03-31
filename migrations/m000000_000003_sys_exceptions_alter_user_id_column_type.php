<?php
declare(strict_types = 1);

use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\traits\traits\MigrationTrait;
use yii\db\Migration;

/**
 * m000000_000003_sys_exceptions_alter_user_id_column_type
 */
class m000000_000003_sys_exceptions_alter_user_id_column_type extends Migration {
	use MigrationTrait;

	/**
	 * @return string
	 */
	public static function mainTableName():string {
		return SysExceptions::tableName();
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn(self::mainTableName(), 'user_id', $this->string(255));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn(self::mainTableName(), 'user_id', $this->integer());
	}
}