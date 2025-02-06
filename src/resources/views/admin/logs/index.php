<?php

use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\widgets\GridView;

/**
 * @var \app\modules\admin\search\LogSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Логи');

?>
    <div class="d-grid d-md-flex justify-content-md-end gap-2 gap-md-0 mb-2">
        <?= Html::tag('i', '', [
            'id' => 'searchCardButton',
            'class' => 'btn btn-danger bi bi-funnel',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
            'title' => Yii::t('views', 'Фильтры поиска'),
        ]); ?>
    </div>

    <?php Pjax::begin(['id' => 'logsList', 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'В логах отсутствуют какие-либо сообщения'),
                'columns' => [
                    [
                        'attribute' => 'level',
                        'headerOptions' => ['style' => 'min-width: 10rem; width: 10%']
                    ],
                    [
                        'attribute' => 'log_time',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 10%'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                    [
                        'attribute' => 'category',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 10%'],
                        'contentOptions' => ['class' => 'small']
                    ],
                    [
                        'attribute' => 'prefix',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 10%'],
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => strlen(strip_tags($data->prefix)) > 30 ? mb_substr(strip_tags($data->prefix), 0, 30).' ...' : strip_tags($data->prefix)
                    ],
                    [
                        'attribute' => 'message',
                        'headerOptions' => ['style' => 'min-width: 15rem; width: 20%'],
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => strlen(strip_tags($data->message)) > 150 ? mb_substr(strip_tags($data->message), 0, 150).' ...' : strip_tags($data->message)
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['style' => 'min-width: 4rem; width: 5%'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<i class="bi bi-eye text-dark"></i>',
                                    Url::to(['view', 'id' => $model->id]),
                                    [
                                        'data-pjax' => 0,
                                        'data-bs-toggle' => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'title' => Yii::t('views', 'Подробнее'),
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
    <?php Pjax::end(); ?>