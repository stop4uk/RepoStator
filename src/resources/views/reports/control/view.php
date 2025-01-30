<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    Modal
};

use app\modules\reports\widgets\structform\StructFormWidget;

/**
 * @var \app\modules\reports\models\DataModel $model
 */

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Контроль'), 'url' => Url::to(['/reports/control'])],
    Yii::t('views', 'Просмотр'),
    Yii::t('views', '{group}. {name} от {date}', [
        'group' => $model->group->name,
        'name' => $model->report->name,
        'date' => date('d.m.Y H:i', $model->report_datetime)
    ])
];

?>

<div class="row">
    <div class="col-12 offset-md-5 col-md-7">
        <div class="card">
            <div class="card-body py-0">
                <table class="table table-borderless">
                    <tr>
                        <td class="py-2"></td>
                        <td class="text-end">
                            <?php
                                $ruleArray = [
                                    'created_uid' => $model->entity->created_uid,
                                    'created_gid' => $model->entity->created_gid,
                                    'record_status' => $model->entity->record_status
                                ];

                                if (
                                    $model->changes
                                    && (
                                        Yii::$app->getUser()->can('data.change.main', $ruleArray)
                                        || Yii::$app->getUser()->can('data.change.group', $ruleArray)
                                        || Yii::$app->getUser()->can('data.change.all', $ruleArray)
                                    )
                                ) {
                                    Modal::begin([
                                        'size' => Modal::SIZE_LARGE,
                                        'title' => Yii::t('views', 'Внесенные изменения в отчет'),
                                        'toggleButton' => [
                                            'label' => Yii::t('views', 'Ранее внесенные изменения'),
                                            'class' => 'btn btn-sm btn-primary'
                                        ],
                                    ]);
                                        echo $this->render('_partial/changes', ['changes' => $model->changes]);
                                    Modal::end();
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-0"><?= Yii::t('views', 'Начало отчетного периода'); ?></td>
                        <td class="text-end py-0 fw-bold"><?= date('d.m.Y H:i', $model->report_datetime); ?></td>
                    </tr>
                    <tr>
                        <td class="py-0"><?= Yii::t('views', 'Группа'); ?></td>
                        <td class="text-end py-0 fw-bold"><?= $model->group->name; ?></td>
                    </tr>
                    <tr>
                        <td class="py-0"><?= Yii::t('views', 'Пользователь'); ?></td>
                        <td class="text-end py-0 fw-bold"><?= Yii::$app->getUser()->getIdentity()->shortName; ?></td>
                    </tr>
                </table>

                <?php
                    if ) {$model->getEntity()->record_status) {
                        echo Html::a(
                            Yii::t('views', 'Изменить'),
                            Url::to(['edit', 'id' => $model->getEntity()->id, 'form_control' => true]),
                            ['class' => 'btn btn-dark w-100 mb-2']
                        );
                    } else {
                        $ruleArray = $model->getEntity()->toArray(['created_uid', 'created_gid', 'record_status']);

                        if (
                            Yii::$app->getUser()->can('data.enable.main', $ruleArray)
                            || Yii::$app->getUser()->can('data.enable.group', $ruleArray)
                            || Yii::$app->getUser()->can('data.enable.all', $ruleArray)
                        ) {
                            echo Html::a(
                                Yii::t('views', 'Восстановить запись'),
                                Url::to(['enable', 'id' => $model->getEntity()->id]),
                                ['class' => 'btn btn-dark w-100 mb-2']
                            );
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php
                    if ) {!$model->getEntity()->record_status) {
                        echo Html::tag('div', Yii::t('views', 'Данная запись НЕАКТИВНА'), ['class' => 'alert alert-danger text-center', 'role' => 'alert']);
                    }

                    echo StructFormWidget::widget([
                        'model' => $model,
                        'formId' => 'reportwork-form',
                        'formField' => 'content',
                        'view' => true
                    ]);
                ?>
            </div>
        </div>
    </div>
</div>

