<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\modules\users\components\rbac\RbacHelper;

/**
 * @var \app\modules\reports\models\ConstantModel $model
 */

$this->title = Yii::t('views', 'Редактирование константы');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Константы'), 'url' => Url::to(['/reports/constant'])],
];

?>

<div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
    <?php
    $ruleArray = $model->getEntity()->toArray(['created_uid', 'created_gid', 'record_status']);
    $rolesArray = ['constant.delete.main', 'constant.delete.group', 'constant.delete.all',];

    if (
        $model->getEntity()->record_status
        && RbacHelper::canArray($rolesArray, $ruleArray)
    ) {
        echo Html::a(
            Yii::t('views', 'Удалить'),
            Url::to(['delete', 'id' => $model->getEntity()->id, 'fromEdit' => true]),
            [
                'id' => "deleteButton_{$model->getEntity()->id}",
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('views', 'Вы действительно хотите удалить константу "{name}"?', ['name' =>  $model->name]),
                ]
            ]
        );
    }
    ?>
</div>
<div class="card">
    <div class="card-body">
        <?= $this->render('_partial/form', compact('model')); ?>
    </div>
</div>