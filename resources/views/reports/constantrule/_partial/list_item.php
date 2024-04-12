<?php

/**
 * @var \app\entities\report\ReportConstantRuleEntity $model
 */

use yii\helpers\{
    Url,
    Json
};
use yii\bootstrap5\Html;
use app\helpers\CommonHelper;

?>

<div class="card mb-2">
    <div class="card-body pt-2 pb-2">
        <div class="row">
            <div class="col-8">
                <div class="col-12">
                    <?= Html::tag('span', $model->name, ['class' => 'h5']) . ' ' . Html::tag('span', "#{$model->record}", ['class' => 'text-muted small']); ?>
                </div>
                <div class="col-12 mt-1 text-muted small">
                    <?php
                        preg_match_all('/\"(.*?)\"/', $model->rule, $constants);
                        $countRuleMessage = Yii::t('views', '{n, plural, =1{одна константа} one{# константа} few{# константы} many{# констант} other{# констант}}', ['n' => count($constants[1] ?: 0)]);

                        echo Html::tag('span', $countRuleMessage, ['class' => 'badge bg-primary me-1']);

                        if ( $model->description ) {
                            $description = strip_tags(Json::decode($model->description));
                            echo strlen(strip_tags($description)) > 50 ? mb_substr(strip_tags($description), 0, 50).' ...' : strip_tags($description);
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

                        if ( $model->report_id ) {
                            echo Html::tag('span', '<i class="bi bi-exclamation-triangle p-1 me-2"></i>', [
                                'class' => 'text-danger fw-bold',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Доступно для конкретного отчета'),
                            ]);
                        }

                        if ( $model->groups_only ) {
                            echo Html::tag('span', '<i class="bi bi-collection p-1 me-2"></i>', [
                                'class' => 'text-danger fw-bold',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Расчет для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($model->groups_only))]),
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
                            Yii::$app->getUser()->can('constantRule.view.main', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.view.group', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.view.all', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.view.delete.main', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.view.delete.group', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.view.delete.all', $ruleArray)
                        ) {
                            echo  Html::a('<i class="bi bi-eye text-dark me-2"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            Yii::$app->getUser()->can('constantRule.edit.main', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.edit.group', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.edit.all', $ruleArray)
                        ) {
                            echo  Html::a('<i class="bi bi-pen text-dark me-2"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            Yii::$app->getUser()->can('constantRule.delete.main', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.delete.group', $ruleArray)
                            || Yii::$app->getUser()->can('constantRule.delete.all', $ruleArray)
                        ) {
                            echo Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                'role' => 'button',
                                'data-message' => Yii::t('views', 'Вы действительно хотите удалить правило "{name}"?', ['name' =>  $model->name]),
                                'data-url' => Url::to(['delete', 'id' => $model->id]),
                                'data-pjaxContainer' => '#constantrulesList',
                                'onclick' => 'workWithRecord($(this))',
                            ]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>