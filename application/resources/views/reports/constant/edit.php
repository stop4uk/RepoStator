<?php

use yii\helpers\Url;

/**
 * @var \app\models\report\ConstantModel $model
 */

$this->title = Yii::t('views', 'Редактирование константы');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Константы'), 'url' => Url::to(['/reports/constant'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>