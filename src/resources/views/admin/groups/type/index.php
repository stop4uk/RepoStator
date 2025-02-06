<?php

use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\helpers\CommonHelper;
use app\widgets\GridView;
use app\modules\users\components\rbac\items\Roles;

/**
 * @var \app\modules\admin\search\GroupTypeSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Список типов групп');

?>
    <div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
        <?= Html::a(Yii::t('views', 'Новый тип группы'), ['create'], ['class' => 'btn btn-primary me-md-2']); ?>
        <?= Html::tag('i', '', [
            'id' => 'searchCardButton',
            'class' => 'btn btn-danger bi bi-funnel',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
            'title' => Yii::t('views', 'Фильтры поиска'),
        ]); ?>
    </div>

    <?php Pjax::begin(['id' => 'groupsTypeList', 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Типы групп для просмотра отсутствуют'),
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['style' => 'min-width: 4rem; width: 5%'],
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'min-width: 20rem; width: 60%'],
                        'value' => function($data) {
                            $resultString = Html::tag('i', '', [
                                'class' => 'bi bi-circle-fill me-2 text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Состояние записи: {status}', ['status' =>  CommonHelper::getYesOrNoRecord($data->record_status)])
                            ]);

                            if ($data->description) {
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

                            $resultString .= $data->name;

                            return $resultString;
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['style' => 'min-width: 8rem; width: 10%'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{view} {edit} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="bi bi-eye text-dark"></i>', Url::to(['view', 'id' => $model->id]), [
                                    'data-pjax' => 0,
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Просмотреть'),
                                ]);
                            },
                            'edit' => function($url, $model) {
                                return Html::a( '<i class="bi bi-pen text-dark"></i>', Url::to(['edit', 'id' => $model->id]), [
                                    'data-pjax' => 0,
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Редактировать'),
                                ]);
                            },
                            'delete' => function($url, $model) {
                                return Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                    'role' => 'button',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Удалить'),
                                    'data-message' => Yii::t('views', 'Вы действительно хотите удалить тип группы "{name}"?', ['name' =>  $model->name]),
                                    'data-url' => Url::to(['delete', 'id' => $model->id]),
                                    'data-pjaxContainer' => '#groupsTypeList',
                                    'onclick' => 'workWithRecord($(this))',
                                ]);
                            }
                        ],
                        'visibleButtons' => [
                            'delete' => function($model) {
                                return (
                                    $model->record_status
                                    && Yii::$app->getUser()->can(Roles::ADMIN, ['id' => $model->id])
                                );
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
    <?php Pjax::end(); ?>
