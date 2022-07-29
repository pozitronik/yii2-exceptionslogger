<?php
declare(strict_types = 1);
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var SysExceptionsSearch $searchModel
 */

use kartik\daterange\DateRangePicker;
use kartik\grid\DataColumn;
use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\sys_exceptions\models\SysExceptionsSearch;
use pozitronik\sys_exceptions\SysExceptionsModule;
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
	'panel' => [
		'heading' => false,
	],
	'replaceTags' => [
		'{logsBtn}' => SysExceptionsModule::a('runtime/logs', 'index/logs', ['class' => 'btn btn-default'])
	],
	'toolbar' => [
		'{logsBtn}'
	],
	'panelBeforeTemplate' => '<div class="float-left">{toolbarContainer}</div>{summary}{before}<div class="clearfix"></div>',
	'columns' => [
		'id',
		[
			'class' => DataColumn::class,
			'attribute' => 'timestamp',
			'format' => 'datetime',
			'filterType' => DateRangePicker::class,
			'filterWidgetOptions' => [
				'convertFormat' => true,
				'hideInput' => true,
				'presetDropdown' => true,
				'pluginOptions' => [
					'timePicker' => false,
					'locale' => [
						'format' => 'Y-m-d',
					]
				]
			]
		],
		'code',
		'statusCode',
		'get',
		'post',
		'message',
		'trace:ntext',
		'known:boolean',
		[
			'class' => DataColumn::class,
			'attribute' => 'user_id',
			'value' => static fn(SysExceptions $model) => $model->user_id,
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'file',
			'value' => static fn(SysExceptions $model) => "{$model->file}:{$model->line}",
			'format' => 'raw'
		],
		'message',
		[
			'class' => ActionColumn::class,
			'template' => '{view} {acknowledge}',
			'buttons' => [
				'acknowledge' => static fn(string $url) => Html::a('', $url, ['class' => 'fa fa-check', 'title' => 'Acknowledge'])
			]
		]
	]
]) ?>
