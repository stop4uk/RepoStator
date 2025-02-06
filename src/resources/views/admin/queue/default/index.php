<?php

use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\widgets\GridView;

/**
 * @var \app\modules\admin\search\QueueSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Очередь задач');

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

    <?php Pjax::begin(['id' => 'queueList', 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Все задачи выполнены. Очередь пуста'),
                'columns' => [
                    'id',
                    [
                        'attribute' => 'job',
                        'headerOptions' => ['style' => 'min-width: 15rem; width: 20%'],
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => strlen(strip_tags($data->job)) > 35 ? mb_substr(strip_tags($data->job), 0, 35).' ...' : strip_tags($data->job)
                    ],
                    [
                        'attribute' => 'channel',
                        'headerOptions' => ['style' => 'min-width: 10rem; width: 12%'],
                    ],
                    [
                        'attribute' => 'delay',
                        'headerOptions' => ['style' => 'min-width: 6rem; width: 10%'],
                    ],
                    [
                        'attribute' => 'attempt',
                        'headerOptions' => ['style' => 'min-width: 2rem; width: 2%'],
                    ],
                    [
                        'attribute' => 'pushed_at',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 10%'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                    [
                        'attribute' => 'done_at',
                        'headerOptions' => ['style' => 'min-width: 15rem; width: 10%'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();
    $this->registerJs(<<<JS
        setInterval(function(){
            $.pjax.reload({container:'#queueList', method: "POST", async: true, push: false , data: $("#searchForm").serialize()});
        }, 15000);
JS);
