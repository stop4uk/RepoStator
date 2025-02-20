<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\modules\users\components\rbac\RbacHelper;

/**
 * @var \app\modules\reports\models\ReportModel $model
 */

$this->title = Yii::t('views', 'Редактирование отчета');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
];

?>

<div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
    <?php
        $ruleArray = $model->getEntity()->toArray(['created_uid', 'created_gid', 'record_status']);
        $rolesArray = ['report.delete.main', 'report.delete.group', 'report.delete.all',];

        if (
                $model->getEntity()->record_status
                && RbacHelper::canArray($rolesArray, $ruleArray)
        ) {
            echo Html::a(
                Yii::t('views', 'Удалить отчет'),
                Url::to(['delete', 'id' => $model->getEntity()->id, 'fromEdit' => true]),
                [
                    'id' => "deleteButton_{$model->getEntity()->id}",
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('views', 'Вы действительно хотите удалить отчет "{name}"?', ['name' =>  $model->name]),
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

