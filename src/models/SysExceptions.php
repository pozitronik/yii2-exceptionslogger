<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use pozitronik\helpers\Utils;
use Yii;
use Throwable;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 *
 * @property int $id
 * @property string $timestamp
 * @property int $user_id
 * @property int $code
 * @property string $file
 * @property int $line
 * @property string $message
 * @property string $trace
 * @property string $get
 * @property string $post
 * @property bool $known
 */
class SysExceptions extends ActiveRecord {

	/**
	 * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
	 * After the SysOptions object is created, if you want to change this property, you should only assign it
	 * with a DB connection object.
	 * This can also be a configuration array for creating the object.
	 */
	public $db = 'db';

	private const DEFAULT_TABLE_NAME = 'sys_exceptions';

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();
		$this->db = Instance::ensure($this->db, Connection::class);
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName():string {
		return ArrayHelper::getValue(Yii::$app->modules, 'sysexceptions.params.tableName', static::DEFAULT_TABLE_NAME);
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['timestamp', 'get', 'post'], 'safe'],
			[['user_id', 'code', 'line'], 'integer'],
			[['message', 'trace'], 'string'],
			[['file'], 'string', 'max' => 255],
			['known', 'boolean']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'timestamp' => 'Время',
			'user_id' => 'Пользователь',
			'code' => 'Код',
			'file' => 'Файл',
			'line' => 'Строка',
			'message' => 'Сообщение',
			'trace' => 'Trace',
			'get' => '$_GET',
			'post' => '$_POST',
			'known' => 'Известная ошибка'
		];
	}

	/**
	 * В случае, если надо поставить отлов и логирование исключения
	 * @param Throwable $t
	 * @param bool $throw Если передано исключение, оно выбросится в случае ненахождения модели
	 * @param bool $known_error Пометить исключение, как известное. Сделано для пометки исключений, с которыми мы ничего сделать не можем (ошибка сторонних сервисов, например).
	 * @return null|string Результат сделан для удобства, и ни на что не влияет
	 * @throws Throwable
	 */
	public static function log(Throwable $t, bool $throw = false, bool $known_error = false):?string {
		$logger = new self();
		try {
			$logger->setAttributes([
				'user_id' => Yii::$app->request->isConsoleRequest?0:Yii::$app->user->id,
				'code' => $t->getCode(),
				'file' => $t->getFile(),
				'line' => $t->getLine(),
				'message' => $t->getMessage(),
				'trace' => $t->getTraceAsString(),
				'get' => json_encode($_GET),
				'post' => json_encode($_POST),
				'known' => $known_error
			]);
			if (!$logger->save()) return Utils::fileLog($logger->attributes, 'exception catch', 'exception.log');
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable $t) {
			return Utils::fileLog($logger->attributes, '!!!exception catch', 'exception.log');
		} finally {
			if ($throw) throw $t;
		}
		return null;
	}

	/**
	 * Acknowledge record
	 * @param int $id
	 * @throws Throwable
	 */
	public static function acknowledgeOne(int $id):void {
		if (null !== $model = self::findOne($id)) $model->updateAttributes(['known' => true]);
	}

	/**
	 * Помечаем все записи, как известные
	 */
	public static function acknowledgeAll():void {
		self::updateAll(['known' => true], ['known' => false]);
	}

	/**
	 * @return int
	 */
	public static function unknownCount():int {
		return self::find()->andOnCondition(['known' => false])->count();
	}

}
