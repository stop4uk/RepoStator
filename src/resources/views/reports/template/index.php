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
                        'headerOptions' => [
                            'width' => '60%'
                        ],
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
                        'format' => 'html',
                        'value' => function($data) {
                            $datetime = match ($data->form_datetime) {
                                ReportFormTemplateEntity::REPORT_DATETIME_WEEK => ['icon' => 'calendar-day', 'message' => Yii::t('views', 'Неделя')],
                                ReportFormTemplateEntity::REPORT_DATETIME_MONTH => ['icon' => 'calendar-month', 'message' => Yii::t('views', 'Месяц')],
                                ReportFormTemplateEntity::REPORT_DATETIME_PERIOD => ['icon' => 'calendar3', 'message' => Yii::t('views', 'Произвольный')],
                            };


                            return Html::tag('i', '', [
                                'class' => "bi bi-{$datetime['icon']} me-2",
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', "Период расчета: {period}", ['period' => $datetime['message']]),
                            ]);
                        }
                    ],
                    [
                        'label' => null,
                        'headerOptions' => [
                            'width' => '8%'
                        ],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = '';

                            if ($data->use_appg) {
                                $resultString .= Html::tag('i', '', [
                                    'class' => 'bi bi-alarm me-2',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Сравнение с АППГ'),
                                ]);
                            }

                            if ($data->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE) {
                                $resultString .= Html::tag('i', '', [
                                    'class' => 'bi bi-file-earmark-spreadsheet me-2',
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
                        'headerOptions' => ['width' => '10%'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{view} {edit} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="bi bi-eye text-dark"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                            },
                            'edit' => function($url, $model) {
                                return Html::a('<i class="bi bi-pen text-dark"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                            },
                            'delete' => function($url, $model) {
                                return Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                    'role' => 'button',
                                    'data-message' => Yii::t('views', 'Вы действительно хотите удалить шаблон "{name}"?', ['name' =>  $model->name]),
                                    'data-url' => Url::to(['delete', 'id' => $model->id]),
                                    'data-pjaxContainer' => '#templatesList',
                                    'onclick' => 'workWithRecord($(this))',
                                ]);
                            }
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
                            'delete' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'template.delete.main',
                                    'template.delete.group',
                                    'template.delete.all',
                                ];

                                return $model->record_status && RbacHelper::canArray($rolesArray, $ruleArray);
                            }
                        ]
                    ],
                ]
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();