<?php

use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var \app\modules\admin\search\LogSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Логи');

?>
    <div class="d-flex justify-content-end mb-2">
        <?= Html::tag('i', '', [
            'id' => 'searchCardButton',
            'class' => 'btn btn-danger bi bi-funnel',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
            'title' => Yii::t('views', 'Фильтры поиска'),
        ]); ?>
    </div>

<?php Pjax::begin(['id' => 'logsList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table'],
                'emptyText' => Yii::t('views', 'В логах отсутствуют какие-либо сообщения'),
                'columns' => [
                    'level',
                    [
                        'attribute' => '',
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                    [
                        'attribute' => 'category',
                        'contentOptions' => ['class' => 'small']
                    ],
                    [
                        'attribute' => 'prefix',
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => strlen(strip_tags($data->prefix)) > 30 ? mb_substr(strip_tags($data->prefix), 0, 30).' ...' : strip_tags($data->prefix)
                    ],
                    [
                        'attribute' => 'message',
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => strlen(strip_tags($data->message)) > 150 ? mb_substr(strip_tags($data->message), 0, 150).' ...' : strip_tags($data->message)
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['width' => '10%'],
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
<?php
    Pjax::end();