<?php

use yii\helpers\Url;

/**
 * @var \app\modules\users\models\GroupModel $model
 */

$this->title = Yii::t('views', 'Редактирование группы');

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
