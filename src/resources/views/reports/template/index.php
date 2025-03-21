<?php

use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\helpers\CommonHelper;
use app\widgets\GridView;
use app\modules\reports\entities\ReportFormTemplateEntity;
use app\modules\users\components\rbac\{
    items\Permissions,
    RbacHelper
};


/**
 * @var \app\modules\reports\search\TemplateSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Список шаблонов');

?>
    <div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
        <?php
            if (Yii::$app->getUser()->can(Permissions::TEMPLATE_CREATE)) {
                echo Html::a(Yii::t('views', 'Новый шаблон'), ['create'], ['class' => 'btn btn-primary me-md-2']);
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

    <?php Pjax::begin(['id' => 'templatesList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>

    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Шаблоны отсутствуют'),
                'tableOptions' => ['class' => 'table'],
                'columns' => [
                    [
                        'attribute' => 'name',
                        'headerOptions' => ['style' => 'min-width: 18rem; width: 60%'],
                        'format' => 'raw',
                        'value' => function($data) {
                            return Html::tag('i', '', [
                                'class' => 'bi bi-circle-fill me-2 text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Статус записи: {status}', ['status' => CommonHelper::getYesOrNoRecord($data->record_status)])
                            ]) . $data->name . Html::tag('span', "#{$data->report->name}", ['class' => 'ms-1 text-muted small']);
                        }
                    ],
                    [
                        'label' => null,
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'min-width: 1.5rem;'],
                        'value' => function($data) {
                            $datetime = match ($data->form_datetime) {
                                ReportFormTemplateEntity::REPORT_DATETIME_WEEK => ['icon' => 'calendar-day', 'message' => Yii::t('views', 'Неделя')],
                                ReportFormTemplateEntity::REPORT_DATETIME_MONTH => ['icon' => 'calendar-month', 'message' => Yii::t('views', 'Месяц')],
                                ReportFormTemplateEntity::REPORT_DATETIME_PERIOD => ['icon' => 'calendar3', 'message' => Yii::t('views', 'Произвольный')],
                            };


                            return Html::tag('i', '', [
                                'class' => 'bi bi-' . $datetime['icon'],
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', "Период расчета: {period}", ['period' => $datetime['message']]),
                            ]);
                        }
                    ],
                    [
                        'label' => null,
                        'headerOptions' => ['style' => 'min-width: 3.5rem'],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = '';

                            if ($data->use_appg) {
                                $resultString .= Html::tag('i', '', [
                                    'class' => 'bi bi-alarm me-1',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Сравнение с АППГ'),
                                ]);
                            }

                            if ($data->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE) {
                                $resultString .= Html::tag('i', '', [
                                    'class' => 'bi bi-file-earmark-spreadsheet',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Формируется из шаблона'),
                                ]);
                            }

                            if ($data->form_usejobs) {
                                $resultString .= Html::tag('i', '', [
                                    'class' => 'bi bi-person-raised-hand',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Используется очередь задач'),
                                ]);
                            }

                            return $resultString;
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['style' => 'min-width: 4rem; width: 8%'],
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
                                    'template.view.main',
                                    'template.view.group',
                                    'template.view.all',
                                    'template.view.delete.main',
                                    'template.view.delete.group',
                                    'template.view.delete.all'
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                            'edit' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'template.edit.main',
                                    'template.edit.group',
                                    'template.edit.all',
                                ];

                                return $model->record_status && RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                        ]
                    ],
                ]
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();