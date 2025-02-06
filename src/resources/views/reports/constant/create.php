<?php

use yii\helpers\Url;

/**
 * @var \app\modules\reports\models\ConstantModel $model
 */

$this->title = Yii::t('views', 'Новая константа');

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