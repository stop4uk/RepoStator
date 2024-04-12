<?php

/**
 * @var \app\models\report\StructureModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Редактирование структуры');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Структуры'), 'url' => Url::to(['/reports/structure'])],
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

