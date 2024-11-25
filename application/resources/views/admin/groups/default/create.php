<?php

use yii\helpers\Url;

/**
 * @var \app\models\group\GroupModel $model
 */

$this->title = Yii::t('views', 'Новая группа');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>
