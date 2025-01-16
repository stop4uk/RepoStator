<?php

use yii\helpers\Url;

/**
 * @var \app\useCases\reports\models\TemplateModel $model
 */

$this->title = Yii::t('views', 'Новый шаблон');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Шаблоны'), 'url' => Url::to(['/reports/template'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>