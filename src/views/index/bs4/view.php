<?php
declare(strict_types = 1);
/**
 * Шаблон страницы просмотра ошибки
 *
 * @var View $this
 * @var SysExceptions $model
 */

use pozitronik\sys_exceptions\models\SysExceptions;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\web\View;
use yii\widgets\DetailView;

$this->title = $model->file;

?>
<div class="panel">
	<div class="panel-heading panel-hdr">
		<h2 class="panel-title"><?= $this->title ?></h2>
		<div class="panel-control panel-toolbar">
			<?= SysExceptionsModule::a('Acknowledge', ['index/acknowledge'], ['class' => 'btn btn-success ']) ?>
		</div>

	</div>
	<div class="panel-body panel-container show">
		<div class="panel-content">
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'id',
					'timestamp',
					[
						'attribute' => 'user_id',
						'value' => $model->user_id,
						'format' => 'raw'
					],
					'code',
					'statusCode',
					'file',
					'line',
					'message',
					'trace:ntext',
					'get',
					'post',
					'known:boolean',
					'user_agent'
				]
			]) ?>
		</div>
	</div>
</div>
