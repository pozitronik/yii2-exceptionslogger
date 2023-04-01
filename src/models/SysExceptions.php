<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use pozitronik\sys_exceptions\SysExceptionsModule;
use pozitronik\traits\traits\ActiveRecordTrait;
use Yii;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\di\Instance;

/**
 *
 * @property int $id
 * @property string $timestamp
 * @property string|null $user_id
 * @property int $code
 * @property int $statusCode
 * @property string $file
 * @property int $line
 * @property string $message
 * @property string $trace
 * @property string $get
 * @property string $post
 * @property bool $known
 * @property string $custom_data
 */
class SysExceptions extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * Enables logging to Yii::error even when message logged to db, i.e. for all errors
	 * @var bool
	 */
	public static bool $yiiErrorLog = false;
	/**
	 * custom_data value handler: null - no data, string - string, function - execution result
	 * @var null|string|callable
	 */
	public static mixed $customDataHandler = null;

	/**
	 * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
	 * After the SysOptions object is created, if you want to change this property, you should only assign it
	 * with a DB connection object.
	 * This can also be a configuration array for creating the object.
	 */
	public Connection|array|string $db = 'db';

	private const DEFAULT_TABLE_NAME = 'sys_exceptions';

	/**
	 * @inheritdoc
	 */
	public function init():void {
		parent::init();
		$this->db = Instance::ensure($this->db, Connection::class);
		static::$yiiErrorLog = SysExceptionsModule::param('yiiErrorLog', static::$yiiErrorLog);
		static::$customDataHandler = SysExceptionsModule::param('customDataHandler', static::$customDataHandler);
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName():string {
		return SysExceptionsModule::param('tableName', self::DEFAULT_TABLE_NAME);
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['timestamp', 'get', 'post', 'user_id', 'custom_data'], 'safe'],
			[['code', 'line', 'statusCode'], 'integer'],
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
			'statusCode' => 'HTTP-код статуса',
			'file' => 'Файл',
			'line' => 'Строка',
			'message' => 'Сообщение',
			'trace' => 'Trace',
			'get' => '$_GET',
			'post' => '$_POST',
			'known' => 'Известная ошибка',
			'custom_data' => 'Произвольные данные'
		];
	}

	/**
	 * В случае, если надо поставить отлов и логирование исключения
	 * @param Throwable $t
	 * @param bool $throw Если передано исключение, оно выбросится в случае ненахождения модели
	 * @param bool $known_error Пометить исключение, как известное. Сделано для пометки исключений, с которыми мы ничего сделать не можем (ошибка сторонних сервисов, например).
	 * @return null|int id добавленной записи, если доступно.
	 * @throws Throwable
	 */
	public static function log(Throwable $t, bool $throw = false, bool $known_error = false):?int {
		$logger = new self();
		try {
			$logger->setAttributes([
				'user_id' => Yii::$app->user?->id?:Yii::$app->user->id,
				'statusCode' => $t->statusCode??null,
				'code' => $t->getCode(),
				'file' => $t->getFile(),
				'line' => $t->getLine(),
				'message' => $t->getMessage(),
				'trace' => $t->getTraceAsString(),
				'get' => json_encode(Yii::$app->request->get()),
				'post' => json_encode(Yii::$app->request->post()),
				'known' => $known_error,
				'custom_data' => static::getCustomData()
			]);
			if ($logger->save()) {
				if (static::$yiiErrorLog) Yii::error($logger->attributes, 'sys.exceptions');
				return $logger->id;
			}
			Yii::error($logger->attributes, 'sys.exceptions');
		} catch (Throwable $t) {
			Yii::error($logger->attributes, 'sys.exceptions');
		} finally {
			if ($throw) throw $t;
		}
		return null;
	}

	/**
	 * Returns custom_data filed value, extracted with preconfigured function
	 * @return null|string
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	private static function getCustomData():?string {
		return (is_callable(static::$customDataHandler))
			?call_user_func(static::$customDataHandler)
			:static::$customDataHandler;
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
