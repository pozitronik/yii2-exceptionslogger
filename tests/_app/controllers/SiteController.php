<?php
declare(strict_types = 1);

namespace app\controllers;

use Tests\Functional\RequestParamsCest;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * class SiteController
 */
class SiteController extends Controller {

	public $enableCsrfValidation = false;

	/**
	 * @return string
	 */
	public function actionError():string {
		$exception = Yii::$app->errorHandler->exception;

		if (null !== $exception) {
			return Html::encode($exception->getMessage());
		}
		return "Status: {$exception->statusCode}";
	}

	/**
	 * @return string
	 * @throws HttpException
	 * @see RequestParamsCest
	 */
	public function actionFail():string {
		throw new HttpException(418, "I'm a teapot");
	}
}

