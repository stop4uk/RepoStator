<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\modules\reports\helpers\JobHelper;

/**
 * @var \yii\data\ActiveDataProvider $queueTemplates
 * @var \yii\data\ActiveDataProvider $needSentData
 * @var array $reports
 * @var array $sentData
 */

Pjax::begin(['id' => 'dashBoard', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);

?>

<?php if (Yii::$app->getUser()->can('data.send') && $needSentData->getTotalCount()): ?>
    <div class="card">
        <div class="card-header">
            <?= Yii::t('views', 'Напоминания'); ?>
        </div>
        <div class="card-body">
            <?php
                echo Html::beginTag('div', ['class' => 'row']);
                foreach ($needSentData->getModels() as $index => $model) {
                    $timePeriodMessage = Yii::t(
                        'views',
                        $model->timePeriod ? 'Период с {start} по {end}' : 'Без ограничений передачи', [
                            'start' => isset($model->timePeriod->start) ? date('d.m.Y H:i', $model->timePeriod->start) : null,
                            'end' => isset($model->timePeriod->end) ? date('d.m.Y H:i', $model->timePeriod->end) : null
                        ]
                    );

                    if ( $model->timePeriod ) {
                        echo Html::beginTag('div', ['class' => 'col-12']);
                        echo Html::tag(
                            name: 'p',
                            content: Yii::t('views', 'Доступной группой не передан отчет "<strong>{name}</strong>" <span class="small">#{period}</span>',
                                [
                                    'name' => $model->name,
                                    'period' => $timePeriodMessage
                                ]
                            ),
                            options: ['class' => 'border border-danger rounded p-2 text-justify']
                        );
                        echo Html::endTag('div');
                    }
                }
                echo Html::endTag('div');
            ?>
        </div>
    </div>
<?php endif; ?>

<?php if (Yii::$app->getUser()->can('statistic')): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <?= Yii::t('views', 'Очередь формирования отчетов'); ?>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $queueTemplates,
                        'emptyText' => Yii::t('views', 'Завершенных или активных задач на фомирование отчетов нет'),
                        'columns' => [
                            [
                                'attribute' => 'job_status',
                                'format' => 'html',
                                'value' => fn($data) => JobHelper::statusNameInColor($data->job_status)
                            ],
                            [
                                'attribute' => 'template_id',
                                'format' => 'html',
                                'value' => function($data) {
                                    $value = $data->template->name . Html::tag('span', ' #' . $data->report->name, ['class' => 'small text-muted']) . '<br />';
                                    $value .= $data->form_period;

                                    return $value;
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'contentOptions' => ['class' => 'small'],
                                'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')]
                            ],
                            [
                                'attribute' => 'updated_at',
                                'contentOptions' => ['class' => 'small'],
                                'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')]
                            ],
                            [
                                'class' => ActionColumn::class,
                                'header' => false,
                                'headerOptions' => ['width' => '10%'],
                                'contentOptions' => ['class' => 'text-center'],
                                'template' => '{download}',
                                'buttons' => [
                                    'download' => function($url, $model) {
                                        return Html::a('<i class="bi bi-file-arrow-down text-dark"></i>',
                                            Url::to(['download', 'path' => base64_encode($model->file)]),
                                            [
                                                'data-pjax' => 0,
                                                'data-bs-toggle' => 'tooltip',
                                                'data-bs-placement' => 'bottom',
                                                'title' => Yii::t('views', 'Скачать'),
                                            ]
                                        );
                                    },
                                ],
                                'visibleButtons' => [
                                    'download' => fn($model) => $model->file
                                ]
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
    Pjax::end();

    $this->registerJs(<<<JS
        setInterval(function(){
            $.pjax.reload({container:'#dashBoard', method: "POST", async: true, push: false});
        }, 20000);
JS);
