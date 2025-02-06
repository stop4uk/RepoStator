<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
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
                        'format' => 'html',
                        'headerOptions' => ['style' => 'min-width: 8rem'],
                        'value' => fn($data) => JobHelper::statusNameInColor($data->job_status)
                    ],
                    [
                        'attribute' => 'template_id',
                        'format' => 'html',
                        'headerOptions' => ['style' => 'min-width: 14rem'],
                        'value' => function($data) {
                            $value = $data->template->name . Html::tag('span', ' #' . $data->report->name, ['class' => 'small text-muted']) . '<br />';
                            $value .= $data->form_period;

                            return $value;
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'contentOptions' => ['class' => 'small text-center'],
                        'headerOptions' => ['style' => 'min-width: 8rem', 'class' => 'text-center'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')]
                    ],
                    [
                        'attribute' => 'updated_at',
                        'contentOptions' => ['class' => 'small text-center'],
                        'headerOptions' => ['style' => 'min-width: 8rem', 'class' => 'text-center'],
                        'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')]
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['style' => 'min-width: 2rem'],
                        'template' => '{download}',
                        'buttons' => [
                            'download' => function($url, $model) {
                                if ($model->file_name) {
                                    $fileName = implode('.', [$model->file_name, $model->file_extension]);
                                    $params = serialize([
                                        'storageID' => $model->storage,
                                        'pathToFile' => $model->file_path . $fileName,
                                        'fileName' => $fileName
                                    ]);

                                    return Html::a(
                                        '<i class="bi bi-file-arrow-down text-dark"></i>',
                                        Url::to(['getfiledirect', 'params' => base64_encode($params)]),
                                        [
                                            'data-pjax' => 0,
                                            'data-bs-toggle' => 'tooltip',
                                            'data-bs-placement' => 'bottom',
                                            'title' => Yii::t('views', 'Скачать'),
                                        ]
                                    );
                                }
                            },
                        ],
                        'visibleButtons' => [
                            'download' => fn($model) => $model->file_name
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();