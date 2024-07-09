<?php

/**
 * @var \app\entities\report\ReportConstantEntity $model
 */

use yii\helpers\Url;
use yii\bootstrap5\Html;
use app\helpers\CommonHelper;

$blockedRecord = (bool)!$model->record_status;

?>

<div class="card mb-2">
    <div class="card-body pt-2 pb-2">
        <div class="row">
            <div class="col-8">
                <div class="col-12">
                    <?= Html::tag('span', $model->name, ['class' => 'h5 ' . ($blockedRecord ? 'text-muted' : '')]) . ' ' . Html::tag('span', "#{$model->record}", ['class' => 'text-muted small']); ?>
                </div>
                <div class="col-12 mt-1 text-muted small">
                    <?php if ( $model->name_full ) {
                        echo Html::tag(
                            'span',
                            strlen(strip_tags($model->name_full)) > 50 ? mb_substr(strip_tags($model->name_full), 0, 50).' ...' : strip_tags($model->name_full),
                            [
                                'class' => ($blockedRecord ? 'text-muted' : '')
                            ]
                        );
                    } else echo '&nbsp;' ?>
                </div>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-center">
                <div>
                    <?php
                        echo Html::tag('i', '', [
                            'class' => 'me-2 bi bi-circle-fill text-' . CommonHelper::getYesOrNoRecordColor($model->record_status),
                            'data-bs-toggle' => 'tooltip',
                            'data-bs-placement' => 'bottom',
                            'title' => Yii::t('views', 'Статус записи: {status}', ['status' => CommonHelper::getYesOrNoRecord($model->record_status)])
                        ]);

                        if ( $model->reports_only ) {
                            echo Html::tag('span', '<i class="bi bi-exclamation-triangle p-1 me-2"></i>', [
                                'class' => 'text-danger fw-bold',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Доступна только для {n, plural, =1{одного отчета} one{# отчета} few{# отчетов} many{# отчетов} other{# отчетов}}', ['n' => count(CommonHelper::explodeField($model->reports_only))]),
                            ]);
                        }

                        if ( $model->union_rules ) {
                            echo Html::tag('span', '<i class="bi bi-columns-gap p-1 me-2"></i>', [
                                'class' => 'text-primary fw-bold',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Правило группировки: {rule}', ['rule' => $model->union_rules]),
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
                            Yii::$app->getUser()->can('constant.view.main', $ruleArray)
                            || Yii::$app->getUser()->can('constant.view.group', $ruleArray)
                            || Yii::$app->getUser()->can('constant.view.all', $ruleArray)
                            || Yii::$app->getUser()->can('constant.view.delete.main', $ruleArray)
                            || Yii::$app->getUser()->can('constant.view.delete.group', $ruleArray)
                            || Yii::$app->getUser()->can('constant.view.delete.all', $ruleArray)
                        ) {
                            echo  Html::a('<i class="bi bi-eye text-dark me-2"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            !$blockedRecord
                            && (
                                Yii::$app->getUser()->can('constant.edit.main', $ruleArray)
                                || Yii::$app->getUser()->can('constant.edit.group', $ruleArray)
                                || Yii::$app->getUser()->can('constant.edit.all', $ruleArray)
                            )
                        ) {
                            echo  Html::a('<i class="bi bi-pen text-dark me-2"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            !$blockedRecord
                            && (
                                Yii::$app->getUser()->can('constant.delete.main', $ruleArray)
                                || Yii::$app->getUser()->can('constant.delete.group', $ruleArray)
                                || Yii::$app->getUser()->can('constant.delete.all', $ruleArray)
                            )
                        ) {
                            echo Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                'role' => 'button',
                                'data-message' => Yii::t('views', 'Вы действительно хотите удалить константу "{name}"?', ['name' =>  $model->name]),
                                'data-url' => Url::to(['delete', 'id' => $model->id]),
                                'data-pjaxContainer' => '#constantList',
                                'onclick' => 'workWithRecord($(this))',
                            ]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>