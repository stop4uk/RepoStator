<?php

/**
 * @var \app\entities\report\ReportFormTemplateEntity $model
 */

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\entities\report\ReportFormTemplateEntity;
use app\helpers\CommonHelper;

$blockedRecord = (bool)!$model->record_status;

?>

<div class="card mb-2">
    <div class="card-body pt-2 pb-2">
        <div class="row">
            <div class="col-6">
                <div class="col-12">
                    <?= Html::tag('span', $model->name, ['class' => 'h5 ' . ($blockedRecord ? 'text-muted' : '')]) . ' ' . Html::tag('span', "#{$model->report->name}", ['class' => 'text-muted small']); ?>
                </div>
            </div>
            <div class="col-4 d-flex align-items-center justify-content-center">
                <div>
                    <?php
                        $datetime = match($model->form_datetime) {
                            ReportFormTemplateEntity::REPORT_DATETIME_WEEK => ['icon' => 'calendar-day', 'message' => Yii::t('views', 'Неделя')],
                            ReportFormTemplateEntity::REPORT_DATETIME_MONTH => ['icon' => 'calendar-month', 'message' => Yii::t('views', 'Месяц')],
                            ReportFormTemplateEntity::REPORT_DATETIME_PERIOD => ['icon' => 'calendar3', 'message' => Yii::t('views', 'Произвольный')],
                        };

                        echo Html::tag('i', '', [
                            'class' => 'me-2 bi bi-circle-fill text-' . CommonHelper::getYesOrNoRecordColor($model->record_status),
                            'data-bs-toggle' => 'tooltip',
                            'data-bs-placement' => 'bottom',
                            'title' => Yii::t('views', 'Статус записи: {status}', ['status' => CommonHelper::getYesOrNoRecord($model->record_status)])
                        ]);

                        echo Html::tag('i', '', [
                            'class' => "bi bi-{$datetime['icon']} me-2",
                            'data-bs-toggle' => 'tooltip',
                            'data-bs-placement' => 'bottom',
                            'title' => Yii::t('views', "Период расчета: {period}", ['period' => $datetime['message']]),
                        ]);

                        if ( $model->use_appg ) {
                            echo Html::tag('i', '', [
                                'class' => 'bi bi-alarm me-2',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Сравнение с АППГ'),
                            ]);
                        }

                        if ( $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE ) {
                            echo Html::tag('i', '', [
                                'class' => 'bi bi-file-earmark-spreadsheet me-2',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Формируется из шаблона'),
                            ]);
                        }

                        if ( $model->form_usejobs ) {
                            echo Html::tag('i', '', [
                                'class' => 'bi bi-person-raised-hand me-2',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Используется очередь задач'),
                            ]);
                        }
                    ?>
                </div>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-end">
                <div>
                    <?php
                        $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);

                        if (
                            Yii::$app->getUser()->can('template.view.main', $ruleArray)
                            || Yii::$app->getUser()->can('template.view.group', $ruleArray)
                            || Yii::$app->getUser()->can('template.view.all', $ruleArray)
                            || Yii::$app->getUser()->can('template.view.delete.main', $ruleArray)
                            || Yii::$app->getUser()->can('template.view.delete.group', $ruleArray)
                            || Yii::$app->getUser()->can('template.view.delete.all', $ruleArray)
                        ) {
                            echo  Html::a('<i class="bi bi-eye text-dark me-2"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            !$blockedRecord
                            && (
                                Yii::$app->getUser()->can('template.edit.main', $ruleArray)
                                || Yii::$app->getUser()->can('template.edit.group', $ruleArray)
                                || Yii::$app->getUser()->can('template.edit.all', $ruleArray)
                            )
                        ) {
                            echo  Html::a('<i class="bi bi-pen text-dark me-2"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            !$blockedRecord
                            && (
                                Yii::$app->getUser()->can('template.delete.main', $ruleArray)
                                || Yii::$app->getUser()->can('template.delete.group', $ruleArray)
                                || Yii::$app->getUser()->can('template.delete.all', $ruleArray)
                            )
                        ) {
                            echo Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                'role' => 'button',
                                'data-message' => Yii::t('views', 'Вы действительно хотите удалить шаблон "{name}"?', ['name' =>  $model->name]),
                                'data-url' => Url::to(['delete', 'id' => $model->id]),
                                'data-pjaxContainer' => '#templatesList',
                                'onclick' => 'workWithRecord($(this))',
                            ]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>