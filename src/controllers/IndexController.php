<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\controllers;

use pozitronik\helpers\BootstrapHelper;
use pozitronik\sys_exceptions\models\LogDownloader;
use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\sys_exceptions\models\SysExceptionsSearch;
use pozitronik\sys_exceptions\SysExceptionsModule;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\RangeNotSatisfiableHttpException;
use yii\web\Response;

/**
 * Class IndexController
 */
class IndexController extends Controller {

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return (null === $viewPath = SysExceptionsModule::param('viewPath'))
			?parent::getViewPath().DIRECTORY_SEPARATOR.(BootstrapHelper::isBs4()?'bs4':'bs3')
			:$viewPath;
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionIndex():string {
		$searchModel = new SysExceptionsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', compact('searchModel', 'dataProvider'));
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

	/**
	 * @param string $mask
	 * @return Response
	 * @throws RangeNotSatisfiableHttpException
	 */
	public function actionLogs(string $mask = '*'):Response {
		return (new LogDownloader(['fileMask' => $mask]))->download();
	}
}
