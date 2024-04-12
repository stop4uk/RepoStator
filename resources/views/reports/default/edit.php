<?php

/**
 * @var \app\models\report\ReportModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Редактирование отчета');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
];

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?= $this->render('_partial/form', compact('model')); ?>
            </div>
        </div>
    </div>
</div>

