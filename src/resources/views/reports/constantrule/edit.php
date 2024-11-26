<?php

use yii\helpers\Url;

/**
 * @var \app\models\report\ConstantRuleModel $model
 */

$this->title = Yii::t('views', 'Редактирование правила');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Правила сложения'), 'url' => Url::to(['/reports/constantrule'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>

