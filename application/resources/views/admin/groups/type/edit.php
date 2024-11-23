<?php

/**
 * @var \app\models\group\GroupTypeModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Редактирование типа группы');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
    ['label' => Yii::t('views', 'Типы'), 'url' => Url::to(['/admin/groups/type'])],
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

