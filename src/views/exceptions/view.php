<?php
declare(strict_types = 1);
/**
 * Шаблон страницы просмотра ошибки
 *
 * @var View $this
 * @var SysExceptions $model
 */

use pozitronik\sys_exceptions\models\SysExceptions;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

$this->title = $model->file;

?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
                    <div class="panel-control">
                        <div class="btn-group">
                            <?= Html::a('Acknowledge', ['acknowledge'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                <h3 class="panel-title"><?= $this->title ?></h3>
            </div>
            <div class="panel-body">
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
</div>
