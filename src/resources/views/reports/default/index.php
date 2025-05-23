<?php

use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\helpers\CommonHelper;
use app\widgets\GridView;
use app\modules\users\components\rbac\{
    items\Permissions,
    RbacHelper
};


/**
 * @var \app\modules\reports\search\ReportSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Список отчетов');

?>
    <div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
        <?php
            if (Yii::$app->getUser()->can(Permissions::REPORT_CREATE)) {
                echo Html::a(Yii::t('views', 'Новый отчет'), ['create'], ['class' => 'btn btn-primary me-md-2']);
            }

            echo Html::tag('i', '', [
                'id' => 'searchCardButton',
                'class' => 'btn btn-danger bi bi-funnel',
                'data-bs-toggle' => 'tooltip',
                'data-bs-placement' => 'bottom',
                'title' => Yii::t('views', 'Фильтры поиска'),
            ]);
        ?>
    </div>

    <?php Pjax::begin(['id' => 'reportList', 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>

    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Отчеты отсутствуют'),
                'columns' => [
                    [
                        'attribute' => 'name',
                        'headerOptions' => ['style' => 'min-width: 18rem; width: 60%'],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = Html::tag('i', '', [
                                'class' => 'bi bi-circle-fill me-2 text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Статус записи: {status}', ['status' => CommonHelper::getYesOrNoRecord($data->record_status)])
                            ]);

                            if($data->description) {
                                $resultString .= Html::tag('i', '', [
                                    'class' => 'me-2 bi bi-question-circle',
                                    'tabindex' => 0,
                                    'role' => 'button',
                                    'title' => Yii::t('views', 'Описание'),
                                    'data-bs-toggle' => 'popover',
                                    'data-bs-content' => $data->description,
                                    'data-bs-html' => 'true',
                                    'data-bs-trigger' => 'focus'
                                ]);
                            }

                            return $resultString . $data->name;
                        }
                    ],
                    [
                        'label' => null,
                        'format' => 'html',
                        'headerOptions' => ['style' => 'width: 12rem;'],
                        'value' => function($data) {
                            if ($data->left_period) {
                                $zero = new \DateTime('@0');
                                $offset = new \DateTime('@' . $data->left_period * 60);
                                $diffs = explode('.', $zero->diff($offset)->format('%m.%d.%h.%i'));
                                $resultString = '';

                                $leftPeriod = Yii::t('views', 'Перерыв передачи: ');
                                foreach ($diffs as $key => $value) {
                                    if ($value) {
                                        switch($key) {
                                            case 0:
                                                $leftPeriod .= Yii::t('views', '{n, plural, =1{# месяц} one{# месяц} few{# месяца} many{# месяцев} other{# месяцев}}', ['n' => $value]);
                                                break;
                                            case 1:
                                                $leftPeriod .= Yii::t('views', ' {n, plural, =1{# день} one{# день} few{# дня} many{# дней} other{# дней}}', ['n' => $value]);
                                                break;
                                            case 2:
                                                $leftPeriod .= Yii::t('views', ' {n, plural, =1{# час} one{# час} few{# часа} many{# часов} other{# часов}}', ['n' => $value]);
                                                break;
                                            case 3:
                                                $leftPeriod .= Yii::t('views', ' {n, plural, =1{# минуту} one{# минуту} few{# минуты} many{# минут} other{# минут}}', ['n' => $value]);
                                                break;
                                        }
                                    }
                                }

                                $resultString .= Html::tag('span', $leftPeriod, ['class' => 'badge bg-primary']);

                                if ($data->block_minutes) {
                                    $resultString .= Html::tag(
                                        'span',
                                        Yii::t('views', 'Закрывается за {n, plural, =1{1 минуту} one{# минуту} few{# минуты} many{# минут} other{# минут}}', ['n' => $data->block_minutes]),
                                        ['class' => 'badge bg-danger']);
                                }

                                return Html::tag('div', $resultString, ['class' => 'd-grid gap-2']);
                            }
                        }
                    ],
                    [
                        'label' => null,
                        'headerOptions' => ['style' => 'min-width: 4rem'],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = '';

                            if ($data->groups_only) {
                                $resultString .= Html::tag('span', '<i class="bi bi-exclamation-triangle ms-1"></i>', [
                                    'class' => 'text-danger fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Доступен только для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($data->groups_only))]),
                                ]);
                            }

                            if ($data->groups_required) {
                                $resultString .= Html::tag('span', '<i class="bi bi-card-checklist ms-1"></i>', [
                                    'class' => 'text-primary fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Обязателен для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($data->groups_required))]),
                                ]);
                            }

                            return $resultString;
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['style' => 'min-width: 6rem; width: 8%'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{view} {edit}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="bi bi-eye text-dark"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0, 'id' => "viewButton_{$model->id}"]);
                            },
                            'edit' => function($url, $model) {
                                return Html::a('<i class="bi bi-pen text-dark"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0, 'id' => "editButton_{$model->id}"]);
                            },
                        ],
                        'visibleButtons' => [
                            'view' => function($model) {
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'report.view.main',
                                    'report.view.group',
                                    'report.view.all',
                                    'report.view.delete.main',
                                    'report.view.delete.group',
                                    'report.view.delete.all'
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                            'edit' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'report.edit.main',
                                    'report.edit.group',
                                    'report.edit.all',
                                ];

                                return $model->record_status && RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                        ]
                    ],
                ]
            ]); ?>
        </div>
    </div>

    <?php Pjax::end(); ?>
