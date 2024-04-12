<?php

/**
 * @var \app\models\group\GroupModel $model
 */

use yii\helpers\Url;

$this->title = Yii::t('views', 'Редактирование группы');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
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

