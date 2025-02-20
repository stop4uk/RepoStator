<?php

use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\helpers\CommonHelper;
use app\widgets\GridView;
use app\modules\users\components\rbac\{
    items\Permissions,
    RbacHelper
};


/**
 * @var \app\modules\reports\search\ConstantruleSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $reportsList
 */

$this->title = Yii::t('views', 'Список правил');

?>
    <div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
        <?php
        if (Yii::$app->getUser()->can(Permissions::CONSTANTRULE_CREATE)) {
            echo Html::a(Yii::t('views', 'Новое правило'), ['create'], ['class' => 'btn btn-primary me-md-2']);
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

    <?php Pjax::begin(['id' => 'constantrulesList', 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>

    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Правила сложения отсутствуют'),
                'columns' => [
                    [
                        'attribute' => 'name',
                        'headerOptions' => ['style' => 'min-width: 18rem; width: 30%'],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = Html::tag('i', '', [
                                'class' => 'bi bi-circle-fill me-2 text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Статус записи: {status}', ['status' => CommonHelper::getYesOrNoRecord($data->record_status)])
                            ]);

                            if($data->description) {
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

                            return $resultString . $data->name . Html::tag('span', '#' . $data->record, ['class' => 'ms-1 small text-muted']);
                        }
                    ],
                    [
                        'label' => null,
                        'format' => 'html',
                        'headerOptions' => ['style' => 'min-width: 4rem; width: 8%'],
                        'value' => function($data) {
                            if (!$data->record_status) {
                                preg_match_all('/\"(.*?)\"/', $data->rule, $constants);
                                $countRuleMessage = Yii::t('views', '{n, plural, =1{одна константа} one{# константа} few{# константы} many{# констант} other{# констант}}', ['n' => count($constants[1] ?: 0)]);

                                return Html::tag('span', $countRuleMessage, ['class' => 'badge bg-primary']);
                            }
                        }
                    ],
                    [
                        'label' => null,
                        'headerOptions' => ['style' => 'min-width: 4rem; width: 8%'],
                        'format' => 'raw',
                        'value' => function($data) {
                            $resultString = '';

                            if ($data->report_id) {
                                $resultString .= Html::tag('span', '<i class="bi bi-exclamation-triangle"></i>', [
                                    'class' => 'text-danger fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Только для "{reportName}"', ['reportName' => $data->report->name]),
                                ]);
                            }

                            if ($data->groups_only) {
                                $resultString .= Html::tag('span', '<i class="bi bi-collection"></i>', [
                                    'class' => 'text-danger fw-bold ms-1',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Расчет для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($data->groups_only))]),
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
                                    'constantRule.view.main',
                                    'constantRule.view.group',
                                    'constantRule.view.all',
                                    'constantRule.view.delete.main',
                                    'constantRule.view.delete.group',
                                    'constantRule.view.delete.all'
                                ];

                                return RbacHelper::canArray($rolesArray, $ruleArray);
                            },
                            'edit' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);
                                $rolesArray = [
                                    'constantRule.edit.main',
                                    'constantRule.edit.group',
                                    'constantRule.edit.all',
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