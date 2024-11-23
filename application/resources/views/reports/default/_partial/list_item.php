<?php

/**
 * @var \app\entities\report\ReportEntity $model
 * @var array $groups
 */

use yii\helpers\Url;
use yii\bootstrap5\Html;
use app\helpers\CommonHelper;

?>

<div class="card mb-2">
    <div class="card-body pt-2 pb-2">
        <div class="row">
            <div class="col-8">
                <div class="col-12">
                    <?= Html::tag('span', $model->name, ['class' => 'h4']); ?>
                </div>
                <?php if ( $model->left_period ): ?>
                    <div class="col-12 mt-1">
                        <?php
                            $zero = new \DateTime('@0');
                            $offset = new \DateTime('@' . $model->left_period * 60);
                            $diffs = explode('.', $zero->diff($offset)->format('%m.%d.%h.%i'));

                            $leftPeriod = Yii::t('views', 'Перерыв передачи: ');
                            foreach ($diffs as $key => $value) {
                                if ( $value ) {
                                    switch($key) {
                                        case 0:
                                            $leftPeriod .= Yii::t('views', '{n, plural, =1{# месяц} one{# месяц} few{# месяца} many{# месяцев} other{# месяцев}}', ['n' => $value]);
                                            break;
                                        case 1:
                                            $leftPeriod .= Yii::t('views', '{n, plural, =1{# день} one{# день} few{# дня} many{# дней} other{# дней}}', ['n' => $value]);
                                            break;
                                        case 2:
                                            $leftPeriod .= Yii::t('views', '{n, plural, =1{# час} one{# час} few{# часа} many{# часов} other{# часов}}', ['n' => $value]);
                                            break;
                                        case 3:
                                            $leftPeriod .= Yii::t('views', '{n, plural, =1{# минуту} one{# минуту} few{# минуты} many{# минут} other{# минут}}', ['n' => $value]);
                                            break;
                                    }
                                }
                            }

                            echo Html::tag('span', $leftPeriod, ['class' => 'badge bg-primary']) . '&nbsp;';

                            if ( $model->block_minutes ) {
                                echo Html::tag(
                                    'span',
                                    Yii::t('views', 'Закрывается за {n, plural, =1{1 минуту} one{# минуту} few{# минуты} many{# минут} other{# минут}}', ['n' => $model->block_minutes]),
                                    ['class' => 'badge bg-danger']);
                            }
                        ?>
                    </div>
                <?php endif; ?>
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
                                'title' => Yii::t('views', 'Доступен только для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($model->groups_only))]),
                            ]);
                        }

                        if ( $model->groups_required ) {
                            echo Html::tag('span', '<i class="bi bi-card-checklist p-1 me-2"></i>', [
                                'class' => 'text-primary fw-bold',
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Обязателен для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($model->groups_required))]),
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
                            Yii::$app->getUser()->can('report.view.main', $ruleArray)
                            || Yii::$app->getUser()->can('report.view.group', $ruleArray)
                            || Yii::$app->getUser()->can('report.view.all', $ruleArray)
                            || Yii::$app->getUser()->can('report.view.delete.main', $ruleArray)
                            || Yii::$app->getUser()->can('report.view.delete.group', $ruleArray)
                            || Yii::$app->getUser()->can('report.view.delete.all', $ruleArray)
                        ) {
                            echo  Html::a('<i class="bi bi-eye text-dark me-2"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            Yii::$app->getUser()->can('report.edit.main', $ruleArray)
                            || Yii::$app->getUser()->can('report.edit.group', $ruleArray)
                            || Yii::$app->getUser()->can('report.edit.all', $ruleArray)
                        ) {
                            echo  Html::a('<i class="bi bi-pen text-dark me-2"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                        }

                        if (
                            Yii::$app->getUser()->can('report.delete.main', $ruleArray)
                            || Yii::$app->getUser()->can('report.delete.group', $ruleArray)
                            || Yii::$app->getUser()->can('report.delete.all', $ruleArray)
                        ) {
                            echo Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                'role' => 'button',
                                'data-message' => Yii::t('views', 'Вы действительно хотите удалить отчет "{name}"?', ['name' =>  $model->name]),
                                'data-url' => Url::to(['delete', 'id' => $model->id]),
                                'data-pjaxContainer' => '#reportList',
                                'onclick' => 'workWithRecord($(this))',
                            ]);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>