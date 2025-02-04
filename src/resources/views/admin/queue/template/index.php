<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\modules\reports\helpers\JobHelper;

/**
 * @var \app\modules\reports\search\JobSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Очередь отчетов');

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

    <?php Pjax::begin(['id' => 'queueTemplateList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body pt-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Выполненные или активные задачи на формирование отчетов отсутствуют'),
                'columns' => [
                    [
                        'attribute' => 'job_status',
                        'headerOptions' => ['style' => 'min-width: 8rem; width: 5%'],
                        'contentOptions' => ['class' => 'small'],
                        'format' => 'html',
                        'value' => fn($data) => JobHelper::statusNameInColor($data->job_status)
                    ],
                    [
                        'attribute' => 'report_id',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 10%'],
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => $data->report->name
                    ],
                    [
                        'attribute' => 'template_id',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 10%'],
                        'contentOptions' => ['class' => 'small'],
                        'value' => fn($data) => $data->template->name
                    ],
                    [
                        'attribute' => 'created_at',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 8%'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                    [
                        'attribute' => 'updated_at',
                        'headerOptions' => ['style' => 'min-width: 12rem; width: 8%'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')],
                    ],
                    [
                        'attribute' => 'file',
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['style' => 'min-width: 2rem; width: 2%', 'class' => 'text-center'],
                        'format' => 'raw',
                        'value' => function($data) {
                            if ($data->file) {
                                return Html::a('<i class="bi bi-file-arrow-down"></i>', Url::to(['download', 'path' => base64_encode($data->file ?? '')]));
                            }
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();