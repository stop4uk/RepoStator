<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;

use app\widgets\GridView;
use app\modules\reports\helpers\JobHelper;

/**
 * @var \yii\web\View $this
 * @var \app\modules\reports\search\StatisticSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\reports\forms\StatisticForm $form
 */

$this->title = Yii::t('views', 'Статистика');

?>

    <?php Pjax::begin(['id' => 'jobsList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>

    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Завершенных или активных задач на фомирование отчетов нет'),
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
                        'headerOptions' => ['style' => 'min-width: 6rem'],
                        'contentOptions' => ['class' => 'text-center'],
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
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <?php Pjax::end(); ?>
    <div class="card">
        <div class="card-header">
            <?= Yii::t('views', 'Формирование отчета'); ?>
        </div>
        <div class="card-body">
            <?= $this->render('_partial/form', ['model' => $form]); ?>
        </div>
    </div>
<?php
    $this->registerJs(<<<JS
        setInterval(function(){
            $.pjax.reload({container:'#jobsList', method: "POST", async: true, push: false , data: $("#searchForm").serialize()});
        }, 10000);
JS);
