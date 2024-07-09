<?php

/**
 * @var \app\entities\report\ReportStructureEntity $model
 */

use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap5\Html;
use app\helpers\CommonHelper;

$blockedRecord = (bool)!$model->record_status;

?>

<div class="card mb-2">
    <div class="card-body pt-2 pb-2">
        <div class="row">
            <div class="col-8">
                <div class="col-12">
                    <?= Html::tag('span', $model->name, ['class' => 'h5 ' . ($blockedRecord ? 'text-muted' : '')]) . ' ' . Html::tag('span', "#{$model->report->name}", ['class' => 'text-muted small']);?>
                </div>
                <div class="col-12 mt-1 text-muted small">
                    <?php
                        if ( !$blockedRecord ) {
                            $dataContent = Json::decode($model->content);
                            $counts = ['groups' => count($dataContent['groups']), 'constants' => 0];

                            foreach ($dataContent['constants'] as $groupItems) {
                                $counts['constants'] += count($groupItems);
                            }

                            $messages = [
                                Yii::t('views', '{n, plural, =1{1 раздел} one{# раздел} few{# раздела} many{# разделов} other{# разделов}}', ['n' => $counts['groups']]),
                                Yii::t('views', '{n, plural, =1{1 показатель} one{# показатель} few{# показателя} many{# показателей} other{# показателей}}', ['n' => $counts['constants']]),
                            ];

                            echo Html::tag('span', implode(' # ', $messages), ['class' => 'badge bg-primary']);
                        }
                    ?>
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

                        if ( $model->groups_only ) {
                            echo Html::tag('span', '<i class="bi bi-exclamation-triangle p-1 me-2"></i>', [
                                'class' => 'text-danger fw-bold',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Доступна только для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($model->groups_only))]),
                            ]);
                        }

                        if ( $model->use_union_rules ) {
                            echo Html::tag('span', '<i class="bi bi-columns-gap p-1 me-2"></i>', [
                                'class' => 'text-primary fw-bold',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Включена группировка констант'),
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
                            Yii::$app->getUser()->can('structure.view.main', $ruleArray)
                            || Yii::$app->getUser()->can('structure.view.group', $ruleArray)
                            || Yii::$app->getUser()->can('structure.view.all', $ruleArray)
                            || Yii::$app->getUser()->can('structure.view.delete.main', $ruleArray)
                            || Yii::$app->getUser()->can('structure.view.delete.group', $ruleArray)
                            || Yii::$app->getUser()->can('structure.view.delete.all', $ruleArray)
                        ) {
                            echo  Html::a('<i class="bi bi-eye text-dark me-2"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            !$blockedRecord
                            && (
                                Yii::$app->getUser()->can('structure.edit.main', $ruleArray)
                                || Yii::$app->getUser()->can('structure.edit.group', $ruleArray)
                                || Yii::$app->getUser()->can('structure.edit.all', $ruleArray)
                            )
                        ) {
                            echo  Html::a('<i class="bi bi-pen text-dark me-2"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            !$blockedRecord
                            && (
                                Yii::$app->getUser()->can('structure.delete.main', $ruleArray)
                                || Yii::$app->getUser()->can('structure.delete.group', $ruleArray)
                                || Yii::$app->getUser()->can('structure.delete.all', $ruleArray)
                            )
                        ) {
                            echo Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                'role' => 'button',
                                'data-message' => Yii::t('views', 'Вы действительно хотите удалить структуру "{name}"?', ['name' =>  $model->name]),
                                'data-url' => Url::to(['delete', 'id' => $model->id]),
                                'data-pjaxContainer' => '#structuresList',
                                'onclick' => 'workWithRecord($(this))',
                            ]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>