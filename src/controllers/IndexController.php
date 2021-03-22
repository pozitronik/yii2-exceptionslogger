<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\controllers;

use pozitronik\sys_exceptions\models\SysExceptions;
use Throwable;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Class IndexController
 */
class IndexController extends Controller {

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$dataProvider = new ActiveDataProvider([
			'query' => SysExceptions::find()->andOnCondition(['known' => false])
		]);

		$mainAttributes = [
			'defaultOrder' => ['timestamp' => SORT_DESC],
			'attributes' => [
				'timestamp',
				'user_id',
				'file',
				'message'
			]
		];

		$dataProvider->setSort($mainAttributes);

		return $this->render('index', [
			'dataProvider' => $dataProvider
		]);
	}

	/**
	 * Подробный просмотр ошибки
	 * @param int $id
	 * @return string
	 */
	public function actionView(int $id):string {
		return $this->render('view', [
			'model' => SysExceptions::findOne(['id' => $id])
		]);
	}

	/**
	 * Acknowledge a problem (or all problems)
	 * @param null|int $id
	 * @throws Throwable
	 */
	public function actionAcknowledge(?int $id = null):void {
		if (null === $id) {
			SysExceptions::acknowledgeAll();
		} else {
			SysExceptions::acknowledgeOne($id);
		}
		$this->redirect(['index']);
	}
}
