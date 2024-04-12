<?php

/**
 * @var \app\models\report\TemplateModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Редактирование шаблона');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Шаблоны'), 'url' => Url::to(['/reports/template'])],
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

