<?php
declare(strict_types = 1);
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use pozitronik\sys_exceptions\models\SysExceptions;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Системные сбои';

?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
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
					return Html::a('', $url, ['class' => 'fa fa-ok', 'title' => 'Acknowledge']);
				}
			]
		]
	]
]) ?>
