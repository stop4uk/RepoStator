<?php

use yii\grid\ActionColumn;
use yii\helpers\{
    Json,
    Url
};
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\helpers\CommonHelper;
use app\widgets\GridView;
use app\modules\users\components\rbac\{
    items\Permissions,
    RbacHelper
};


/**
 * @var \app\modules\reports\search\StructureSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $groupList
 */

$this->title = Yii::t('views', 'Список структур');

?>
    <div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
        <?php
            if (Yii::$app->getUser()->can(Permissions::STRUCTURE_CREATE)) {
                echo Html::a(Yii::t('views', 'Новая стуктура'), ['create'], ['class' => 'btn btn-primary me-md-2']);
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
                'emptyText' => Yii::t('views', 'Стурктуры для просмотра отсутствуют'),
                'tableOptions' => ['class' => 'table'],
                'columns' => [
                    [
                        'attribute' => 'name',
                        'headerOptions' => ['style' => 'min-width: 18rem; width: 30%'],
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
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 10%'],
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
                        'headerOptions' => ['style' => 'min-width: 4rem; width: 8%'],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = '';

                            if ($data->groups_only) {
                                $resultString .= Html::tag('span', '<i class="bi bi-exclamation-triangle"></i>', [
                                    'class' => 'text-danger fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Доступна только для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($data->groups_only))]),
                                ]);
                            }

                            if ($data->use_union_rules) {
                                $resultString .= Html::tag('span', '<i class="bi bi-columns-gap ms-1"></i>', [
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
