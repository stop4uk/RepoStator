<?php

use yii\helpers\Url;

/**
 * @var \app\modules\users\models\GroupTypeModel $model
 */

$this->title = Yii::t('views', 'Редактирование типа группы');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
    ['label' => Yii::t('views', 'Типы'), 'url' => Url::to(['/admin/groups/type'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>
