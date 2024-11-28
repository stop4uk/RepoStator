<?php

use yii\helpers\Url;

/**
 * @var \app\useCases\reports\models\ReportModel $model
 */

$this->title = Yii::t('views', 'Новый отчет');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>