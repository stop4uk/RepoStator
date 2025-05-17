<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\{
    Html,
    Modal
};

use app\widgets\GridView;
use app\helpers\CommonHelper;
use app\modules\users\components\rbac\{
    items\Permissions,
    RbacHelper
};

/**
 * @var \app\modules\reports\search\ConstantSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $reportsList
 */

$this->title = Yii::t('views', 'Контроль за передачей');

?>

    <div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
        <?php
            if (Yii::$app->getUser()->can(Permissions::DATA_CHECKFULL)) {
                Modal::begin([
                    'size' => Modal::SIZE_LARGE,
                    'title' => Yii::t('views', 'Выбор отчета для проверки'),
                    'toggleButton' => [
                        'label' => Yii::t('views', 'Проверка полноты'),
                        'class' => 'btn btn-primary me-md-1'
                    ],
                ]);
                echo $this->render('_partial/checkFull', [
                    'groups' => $searchModel->groups,
                    'reports' => $searchModel->reports
                ]);
                Modal::end();
            }

            if (Yii::$app->getUser()->can(Permissions::DATA_CREATEFOR)) {
                Modal::begin([
                    'size' => Modal::SIZE_LARGE,
                    'title' => Yii::t('views', 'Заполнение отчета за конкретный период'),
                    'toggleButton' => [
                        'label' => Yii::t('views', 'Передача старых данных'),
                        'class' => 'btn btn-dark me-md-2'
                    ],
                ]);
                echo $this->render('_partial/form_createdFor', [
                    'reports' => $searchModel->reports
                ]);
                Modal::end();
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

    <?php Pjax::begin(['id' => 'reportDataList', 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>

    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{pager}{items}{pager}',
                'emptyText' => Yii::t('views', 'Подходящие под контроль сведения отсутствуют, или не указаны фильтры отбора'),
                'columns' => [
                    [
                        'attribute' => 'report_id',
                        'headerOptions' => ['style' => 'min-width: 14rem'],
                        'format' => 'raw',
                        'value' => function($data) {
                            return Html::tag('i','', [
                                    'class' => 'bi bi-circle-fill me-2 text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Статус записи: {status}', ['status' =>  CommonHelper::getYesOrNoRecord($data->record_status)])
                                ]) . $data->report->name . Html::tag('span', '#' . Yii::$app->getFormatter()->asDatetime($data->report_datetime), ['class' => 'ms-1 text-muted small']);
                        }
                    ],
                    [
                        'attribute' => 'created_uid',
                        'headerOptions' => ['style' => 'min-width: 15rem'],
                        'format' => 'html',
                        'value' => fn($data) => $data->createdUser->shortName . Html::tag('span', "#{$data->group->name}", ['class' => 'ms-1 text-muted small'])
                    ],
                    [
                        'attribute' => 'created_at',
                        'headerOptions' => ['style' => 'min-width: 12rem'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['style' => 'min-width: 6rem'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{view} {edit} {delete}',
                        'buttons' => [
                            'view' => fn($url, $model) => Html::a(
                                '<i class="bi bi-eye text-dark"></i>',
                                Url::to(['view', 'id' => $model->id]),
                                [
                                    'data-pjax' => 0,
                                    'id' => "viewButton_{$model->id}",
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Просмотреть'),
                                ]
                            ),
                            'edit' => fn($url, $model) => Html::a(
                                '<i class="bi bi-pen text-dark"></i>',
                                Url::to(['edit', 'id' => $model->id, 'form_control' => true]),
                                [
                                    'data-pjax' => 0,
                                    'id' => "editButton_{$model->id}",
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Редактировать'),
                                ]
                            ),
                            'delete' => fn($url, $model) => Html::tag('span',
                                '<i class="bi bi-trash text-dark"></i>',
                                [
                                    'role' => 'button',
                                    'id' => "deleteButton_{$model->id}",
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Удалить'),
                                    'data-message' => Yii::t('views', 'Вы действительно хотите удалить передачи сведений  по отчету "{reportName}" за отчетный период {reportDate}?', ['reportName' => $model->report->name, 'reportDate' => date('d.m.Y H:i', $model->report_datetime)]),
                                    'data-url' => Url::to(['delete', 'id' => $model->id]),
                                    'data-pjaxContainer' => '#reportDataList',
                                    'onclick' => 'workWithRecord($(this))',
                                ]
                            )
                        ],
                        'visibleButtons' => [
                            'view' => function($url, $model) {
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                return RbacHelper::canArray([
                                    Permissions::DATA_VIEW_MAIN,
                                    Permissions::DATA_VIEW_GROUP,
                                    Permissions::DATA_VIEW_ALL,
                                    Permissions::DATA_VIEW_DELETE_MAIN,
                                    Permissions::DATA_VIEW_DELETE_GROUP,
                                    Permissions::DATA_VIEW_DELETE_ALL
                                ], $ruleArray);
                            },
                            'edit' => function($url, $model) {
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                return $model->record_status && RbacHelper::canArray([
                                        Permissions::DATA_EDIT_MAIN,
                                        Permissions::DATA_EDIT_GROUP,
                                        Permissions::DATA_EDIT_ALL,
                                    ], $ruleArray);
                            },
                            'delete' => function($url, $model) {
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                return $model->record_status && RbacHelper::canArray([
                                        Permissions::DATA_DELETE_MAIN,
                                        Permissions::DATA_DELETE_GROUP,
                                        Permissions::DATA_DELETE_ALL
                                    ], $ruleArray);
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();
