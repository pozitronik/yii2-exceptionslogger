<?php
declare(strict_types = 1);
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var SysExceptionsSearch $searchModel
 */

use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\sys_exceptions\models\SysExceptionsSearch;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\web\View;

$this->title = 'Системные сбои';

?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		'id',
		'timestamp',
		'code',
		'statusCode',
		[
			'attribute' => 'user_id',
			'value' => static function(SysExceptions $model) {
				return $model->user_id;
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'file',
			'value' => static function(SysExceptions $model) {
				return "{$model->file}:{$model->line}";
			},
			'format' => 'raw'
		],
		'message',
		[
			'class' => ActionColumn::class,
			'template' => '{view} {acknowledge}',
			'buttons' => [
				'acknowledge' => static function(string $url) {
					return Html::a('', $url, ['class' => 'fa fa-check', 'title' => 'Acknowledge']);
				}
			]
		]
	]
]) ?>
