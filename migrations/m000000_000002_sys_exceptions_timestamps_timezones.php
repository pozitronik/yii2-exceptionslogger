<?php
declare(strict_types = 1);

use pozitronik\helpers\ArrayHelper;
use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\traits\traits\MigrationTrait;
use yii\db\Migration;

/**
 * m000000_000002_sys_exceptions_timestamps_timezones
 */
class m000000_000002_sys_exceptions_timestamps_timezones extends Migration {
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
		if (null !== ArrayHelper::getValue($this->db->schema->typeMap, 'timestamptz')) {
			$this->alterColumn(self::mainTableName(), 'timestamp', $this->timestamptz(0)->notNull()->comment('Дата и время создания.'));
		} else {
			Yii::info('timestamptz column type is not supported bu DB schema, migration not applied.');
		}

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		if (null !== ArrayHelper::getValue($this->db->schema->typeMap, 'timestamptz')) {
			$this->alterColumn(self::mainTableName(), 'timestamp', $this->timestamp(0)->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('Дата и время создания.'));
		} else {
			Yii::info('timestamptz column type is not supported bu DB schema, migration not applied.');
		}
	}
}