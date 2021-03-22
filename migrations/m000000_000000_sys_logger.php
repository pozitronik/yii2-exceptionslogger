<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m000000_000000_sys_logger
 */
class m000000_000000_sys_logger extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_exceptions', [
			'id' => $this->primaryKey(),
			'timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
			'user_id' => $this->integer(),
			'code' => $this->integer(),
			'file' => $this->string(),
			'line' => $this->integer(),
			'message' => $this->text(),
			'trace' => $this->text(),
			'get' => $this->text()->null()->comment('GET'),
			'post', $this->text()->null()->comment('POST'),
			'known', $this->boolean()->notNull()->defaultValue(false)->comment('Known error')
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_exceptions');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m000000000000_sys_logger cannot be reverted.\n";

		return false;
	}
	*/
}
