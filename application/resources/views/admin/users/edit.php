<?php

/**
 * @var \app\models\user\UserModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Редактирование пользователя');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Пользователи'), 'url' => Url::to(['/admin/users'])],
];

?>

<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>