<?php

use app\widgets\GridView;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/**
 * @var \app\modules\admin\search\QueueSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Очередь задач');

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

<?php Pjax::begin(['id' => 'queueList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table'],
                'emptyText' => Yii::t('views', 'Все задачи выполнены. Очередь пуста'),
                'columns' => [
                    'id',
                    [
                        'attribute' => 'job',
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => strlen(strip_tags($data->job)) > 35 ? mb_substr(strip_tags($data->job), 0, 35).' ...' : strip_tags($data->job)
                    ],
                    'channel',
                    'delay',
                    'attempt',
                    [
                        'attribute' => 'pushed_at',
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                    [
                        'attribute' => 'done_at',
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
