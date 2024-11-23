<?php

/**
 * @var \app\models\report\ConstantRuleModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Редактирование правила');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Правила сложения'), 'url' => Url::to(['/reports/constantrule'])],
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

