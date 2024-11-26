<?php

use yii\helpers\Url;

/**
 * @var \app\models\report\StructureModel $model
 */

$this->title = Yii::t('views', 'Новая структура');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Стурктуры'), 'url' => Url::to(['/reports/structure'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>