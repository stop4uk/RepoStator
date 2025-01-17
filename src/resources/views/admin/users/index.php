<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\helpers\CommonHelper;
use app\useCases\users\helpers\{
    RbacHelper,
    user\UserHelper
};

/**
 * @var \app\useCases\admin\search\UserSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Список пользователей');

?>
    <div class="d-flex justify-content-end mb-2">
        <?php
            if ( Yii::$app->getUser()->can('admin.user.create') ) {
                echo Html::a(Yii::t('views', 'Новый пользователь'), ['create'], ['class' => 'btn btn-primary pt-1 pb-1 me-2']);
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

<?php Pjax::begin(['id' => 'usersList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table'],
                'emptyText' => Yii::t('views', 'Нет доступных пользователей'),
                'columns' => [
                    [
                        'attribute' => 'email',
                        'format' => 'raw',
                        'value' => function($data) {
                            return Html::tag('i', '', [
                                    'class' => 'bi bi-circle-fill me-2 text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Состояние УЗ: {status}', ['status' => CommonHelper::getYesOrNoRecord($data->record_status)])
                                ]) . $data->email;
                        }
                    ],
                    [
                        'attribute' => 'name',
                        'enableSorting' => false,
                        'value' => fn($data) => $data->shortName
                    ],
                    [
                        'attribute' => 'account_status',
                        'format' => 'html',
                        'value' => fn($data) => UserHelper::statusNameInColor($data->account_status)
                    ],
                    [
                        'attribute' => 'hasGroup',
                        'enableSorting' => false,
                        'contentOptions' => ['class' => 'small'],
                        'value' => function($data) use ($searchModel) {
                            if ( $data->group ) {
                                return $searchModel->groups[$data->group->group_id] ?? null;
                            }
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
                            },
                            'edit' => function($url, $model) {
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
                            },
                            'delete' => function($url, $model) {
                                return Html::tag('span',
                                    Html::tag('i', '', ['class' => 'bi bi-trash text-dark']),
                                    [
                                        'role' => 'button',
                                        'data-bs-toggle' => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'title' => Yii::t('views', 'Удалить'),
                                        'data-message' => Yii::t('views', 'Вы действительно хотите удалить пользователя "{name}"?', ['name' =>  $model->shortName]),
                                        'data-url' => Url::to(['delete', 'id' => $model->id]),
                                        'data-pjaxContainer' => '#usersList',
                                        'onclick' => 'workWithRecord($(this))',
                                    ]
                                );
                            }
                        ],
                        'visibleButtons' => [
                            'view' => function($model) {
                                $ruleArray = $model->toArray(['id', 'record_status']);
                                $rolesArray = [
                                    'admin.user.view.group',
                                    'admin.user.view.all',
                                    'admin.user.view.delete.group',
                                    'admin.user.view.delete.all'
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                            'edit' => function($model){
                                $ruleArray = $model->toArray(['id', 'record_status']);
                                $rolesArray = [
                                    'constant.edit.main',
                                    'admin.user.edit.group',
                                    'admin.user.edit.group.all',
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                            'delete' => function($model){
                                $ruleArray = $model->toArray(['id', 'record_status']);
                                $rolesArray = [
                                    'admin.user.delete.group',
                                    'admin.user.delete.all',
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();