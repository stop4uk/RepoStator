<?php

/**
 * @var \app\search\report\ConstantSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $reportsList
 */

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\{
    Html,
    Modal
};

use app\widgets\GridView;
use app\helpers\CommonHelper;

$this->title = Yii::t('views', 'Контроль за передачей');

?>

<div class="d-flex justify-content-end mb-2">
    <?php
        if ( Yii::$app->getUser()->can('data.checkFull') ) {
            Modal::begin([
                'size' => Modal::SIZE_LARGE,
                'title' => Yii::t('views', 'Выбор отчета для проверки'),
                'toggleButton' => [
                    'label' => Yii::t('views', 'Проверка полноты'),
                    'class' => 'btn btn-primary pt-1 pb-1 me-2'
                ],
            ]);
            echo $this->render('_partial/checkFull', [
                'groups' => $searchModel->groups,
                'reports' => $searchModel->reports
            ]);
            Modal::end();
        }

        if ( Yii::$app->getUser()->can('createfor') ) {
            Modal::begin([
                'size' => Modal::SIZE_LARGE,
                'title' => Yii::t('views', 'Заполнение отчета за конкретный период'),
                'toggleButton' => [
                    'label' => Yii::t('views', 'Передача старых данных'),
                    'class' => 'btn btn-dark pt-1 pb-1 me-2'
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

<?php Pjax::begin(['id' => 'reportDataList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]) ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body pt-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table'],
                        'emptyText' => Yii::t('views', 'Подходящие под контроль сведения отсутствуют, или не указаны фильтры отбора'),
                        'columns' => [
                            [
                                'attribute' => 'record_status',
                                'label' => false,
                                'format' => 'raw',
                                'value' => fn($data) => Html::tag(
                                    'i',
                                    '',
                                    [
                                        'class' => 'bi bi-circle-fill text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                        'data-bs-toggle' => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'title' => Yii::t('views', 'Статус записи: {status}', [
                                            'status' =>  CommonHelper::getYesOrNoRecord($data->record_status)
                                        ])
                                    ]
                                )
                            ],
                            [
                                'attribute' => 'report_datetime',
                                'format' => ['date', 'php:d.m.Y H:i'],
                            ],
                            [
                                'attribute' => 'report_id',
                                'value' => fn($data) => $data->report->name
                            ],
                            [
                                'attribute' => 'group_id',
                                'value' => fn($data) => $data->group->name
                            ],
                            [
                                'attribute' => 'created_uid',
                                'value' => fn($data) => $data->createdUser->shortName,
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:d.m.Y H:i'],
                            ],
                            [
                                'class' => ActionColumn::class,
                                'header' => false,
                                'headerOptions' => ['width' => '10%'],
                                'contentOptions' => ['class' => 'text-center'],
                                'template' => '{view} {edit} {delete}',
                                'buttons' => [
                                    'view' => function($url, $model) {
                                        $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);

                                        if (
                                            Yii::$app->getUser()->can('data.view.main', $ruleArray)
                                            || Yii::$app->getUser()->can('data.view.group', $ruleArray)
                                            || Yii::$app->getUser()->can('data.view.all', $ruleArray)
                                            || Yii::$app->getUser()->can('data.view.delete.main', $ruleArray)
                                            || Yii::$app->getUser()->can('data.view.delete.group', $ruleArray)
                                            || Yii::$app->getUser()->can('data.view.delete.all', $ruleArray)
                                        ) {
                                            return Html::a(
                                                '<i class="bi bi-eye text-dark"></i>',
                                                Url::to(['view', 'id' => $model->id]),
                                                [
                                                    'data-pjax' => 0,
                                                    'data-bs-toggle' => 'tooltip',
                                                    'data-bs-placement' => 'bottom',
                                                    'title' => Yii::t('views', 'Просмотреть'),
                                                ]
                                            );
                                        }
                                    },
                                    'edit' => function($url, $model) {
                                        $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);

                                        if (
                                            Yii::$app->getUser()->can('data.edit.main', $ruleArray)
                                            || Yii::$app->getUser()->can('data.edit.group', $ruleArray)
                                            || Yii::$app->getUser()->can('data.edit.all', $ruleArray)
                                        ) {
                                            return Html::a(
                                                '<i class="bi bi-pen text-dark"></i>',
                                                Url::to(['edit', 'id' => $model->id]),
                                                [
                                                    'data-pjax' => 0,
                                                    'data-bs-toggle' => 'tooltip',
                                                    'data-bs-placement' => 'bottom',
                                                    'title' => Yii::t('views', 'Редактировать'),
                                                ]
                                            );
                                        }
                                    },
                                    'delete' => function($url, $model) {
                                        $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);

                                        if (
                                            Yii::$app->getUser()->can('data.delete.main', $ruleArray)
                                            || Yii::$app->getUser()->can('data.delete.group', $ruleArray)
                                            || Yii::$app->getUser()->can('data.delete.all', $ruleArray)
                                        ) {
                                            return Html::tag('span',
                                                Html::tag('i', '', ['class' => 'bi bi-trash text-dark']),
                                                [
                                                    'role' => 'button',
                                                    'data-bs-toggle' => 'tooltip',
                                                    'data-bs-placement' => 'bottom',
                                                    'title' => Yii::t('views', 'Удалить'),
                                                    'data-message' => Yii::t('views', 'Вы действительно хотите удалить передачи сведений  по отчету "{reportName}" за отчетный период {reportDate}?', ['reportName' => $model->report->name, 'reportDate' => date('d.m.Y H:i', $model->report_datetime)]),
                                                    'data-url' => Url::to(['delete', 'id' => $model->id]),
                                                    'data-pjaxContainer' => '#reportDataList',
                                                    'onclick' => 'workWithRecord($(this))',
                                                ]
                                            );
                                        }
                                    }
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end();
