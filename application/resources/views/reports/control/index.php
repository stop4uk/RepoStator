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

/**
 * @var \app\search\report\ConstantSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $reportsList
 */

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
                                'attribute' => 'report_id',
                                'headerOptions' => [
                                    'width' => '40%'
                                ],
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
                                'headerOptions' => [
                                    'width' => '30%'
                                ],
                                'format' => 'html',
                                'value' => fn($data) => $data->createdUser->shortName . Html::tag('span', "#{$data->group->name}", ['class' => 'ms-1 text-muted small'])
                            ],
                            [
                                'attribute' => 'created_at',
                                'headerOptions' => [
                                    'width' => '20%'
                                ],
                                'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
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
