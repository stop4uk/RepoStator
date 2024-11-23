<?php

/**
 * @var \app\models\report\ConstantModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Новая константа');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Константы'), 'url' => Url::to(['/reports/constant'])],
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

