<?php

use yii\helpers\Url;

/**
 * @var \models\ReportModel $model
 */

$this->title = Yii::t('views', 'Редактирование отчета');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>

