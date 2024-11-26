<?php

/**
 * @var \models\user\UserModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Новый пользователь');

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
