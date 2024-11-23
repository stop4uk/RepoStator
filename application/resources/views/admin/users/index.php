<?php

/**
 * @var \app\search\UserSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\helpers\{
    CommonHelper,
    user\UserHelper
};

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

<?php
    Pjax::begin(['id' => 'usersList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);
        echo $this->render('_partial/search', ['searchModel' => $searchModel]);
?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body pt-0">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table'],
                        'emptyText' => Yii::t('views', 'Нет доступных пользователей'),
                        'columns' => [
                            'email',
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
                                        'title' => Yii::t('views', 'Состояние УЗ: {status}', [
                                            'status' =>  CommonHelper::getYesOrNoRecord($data->record_status)
                                        ])
                                    ]
                                )
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
                                        $ruleArray = $model->toArray(['id', 'record_status']);

                                        if (
                                            Yii::$app->getUser()->can('admin.user.view.group', $ruleArray)
                                            || Yii::$app->getUser()->can('admin.user.view.all', $ruleArray)
                                            || Yii::$app->getUser()->can('admin.user.view.delete.group', $ruleArray)
                                            || Yii::$app->getUser()->can('admin.user.view.delete.all', $ruleArray)
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
                                        $ruleArray = $model->toArray(['id', 'record_status']);

                                        if (
                                            Yii::$app->getUser()->can('admin.user.edit.group', $ruleArray)
                                            || Yii::$app->getUser()->can('admin.user.edit.all', $ruleArray)
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
                                        $ruleArray = $model->toArray(['id', 'record_status']);

                                        if (
                                            Yii::$app->getUser()->can('admin.user.delete.group', $ruleArray)
                                            || Yii::$app->getUser()->can('admin.user.delete.all', $ruleArray)
                                        ) {
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