<?php
declare(strict_types = 1);

use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\traits\traits\MigrationTrait;
use yii\db\Migration;

/**
 * m000000_000004_sys_exceptions_add_custom_data_field
 */
class m000000_000004_sys_exceptions_add_custom_data_field extends Migration {
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
		$this->addColumn(self::mainTableName(), 'custom_data', $this->text());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn(self::mainTableName(), 'custom_data');
	}
}