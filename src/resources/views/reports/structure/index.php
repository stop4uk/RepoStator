<?php

use yii\helpers\{
    Url,
    Json
};
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\helpers\{
    CommonHelper,
    RbacHelper
};

/**
 * @var \app\search\report\StructureSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $groupList
 */

$this->title = Yii::t('views', 'Список структур');

?>
    <div class="d-flex justify-content-end mb-2">
        <?php
            if ( Yii::$app->getUser()->can('structure.create') ) {
                echo Html::a(Yii::t('views', 'Новая стуктура'), ['create'], ['class' => 'btn btn-primary pt-1 pb-1 me-2']);
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

<?php Pjax::begin(['id' => 'structuresList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Отчеты отсутствуют'),
                'tableOptions' => ['class' => 'table'],
                'columns' => [
                    [
                        'attribute' => 'name',
                        'headerOptions' => [
                            'width' => '30%'
                        ],
                        'format' => 'raw',
                        'value' => function($data) {
                            return Html::tag('i', '', [
                                'class' => 'bi bi-circle-fill me-2 text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Статус записи: {status}', ['status' => CommonHelper::getYesOrNoRecord($data->record_status)])
                            ]) . $data->name;;
                        }
                    ],
                    [
                        'label' => null,
                        'format' => 'raw',
                        'value' => function($data) {
                            if ($data->record_status) {
                                $dataContent = Json::decode($data->content);
                                $counts = ['groups' => count($dataContent['groups']), 'constants' => 0];

                                foreach ($dataContent['constants'] as $groupItems) {
                                    $counts['constants'] += count($groupItems);
                                }

                                $messages = [
                                    Yii::t('views', '{n, plural, =1{1 раздел} one{# раздел} few{# раздела} many{# разделов} other{# разделов}}', ['n' => $counts['groups']]),
                                    Yii::t('views', '{n, plural, =1{1 показатель} one{# показатель} few{# показателя} many{# показателей} other{# показателей}}', ['n' => $counts['constants']]),
                                ];

                                return Html::tag('span', implode(' # ', $messages), ['class' => 'badge bg-primary d-table-cell']);
                            }
                        }
                    ],
                    [
                        'label' => null,
                        'headerOptions' => [
                            'width' => '10%'
                        ],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = '';

                            if ( $data->groups_only ) {
                                $resultString .= Html::tag('span', '<i class="bi bi-exclamation-triangle p-1"></i>', [
                                    'class' => 'text-danger fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Доступна только для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($data->groups_only))]),
                                ]);
                            }

                            if ( $data->use_union_rules ) {
                                $resultString .= Html::tag('span', '<i class="bi bi-columns-gap p-1 ms-1"></i>', [
                                    'class' => 'text-primary fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Включена группировка констант'),
                                ]);
                            }

                            return $resultString;
                        }
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
                                return Html::a('<i class="bi bi-eye text-dark"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                            },
                            'edit' => function($url, $model) {
                                return Html::a('<i class="bi bi-pen text-dark"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                            },
                            'delete' => function($url, $model) {
                                return Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                    'role' => 'button',
                                    'data-message' => Yii::t('views', 'Вы действительно хотите удалить структуру "{name}"?', ['name' =>  $model->name]),
                                    'data-url' => Url::to(['delete', 'id' => $model->id]),
                                    'data-pjaxContainer' => '#structuresList',
                                    'onclick' => 'workWithRecord($(this))',
                                ]);
                            }
                        ],
                        'visibleButtons' => [
                            'view' => function($model) {
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'structure.view.main',
                                    'structure.view.group',
                                    'structure.view.all',
                                    'structure.view.delete.main',
                                    'structure.view.delete.group',
                                    'structure.view.delete.all'
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                            'edit' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'structure.edit.main',
                                    'structure.edit.group',
                                    'structure.edit.all',
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                            'delete' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'structure.delete.main',
                                    'structure.delete.group',
                                    'structure.delete.all',
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            }
                        ]
                    ],
                ]
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();
