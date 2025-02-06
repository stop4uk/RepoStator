<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\modules\reports\helpers\JobHelper;
use app\modules\users\components\rbac\items\Permissions;

/**
 * @var \yii\data\ActiveDataProvider $queueTemplates
 * @var \yii\data\ActiveDataProvider $needSentData
 * @var array $reports
 * @var array $sentData
 */

Pjax::begin(['id' => 'dashBoard', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);

?>

<?php if (Yii::$app->getUser()->can(Permissions::DATA_SEND)): ?>
    <?php if(!$needSentData->getModels()): ?>
        <div class="alert alert-success text-center fw-bold">
            <?= Yii::t('views', 'Важных напоминаний и уведомлений нет') ?>
        </div>
    <?php else: ?>
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

                    if ($model->timePeriod) {
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
<?php endif; ?>

<?php if (Yii::$app->getUser()->can(Permissions::STATISTIC)): ?>
    <?php if(!$queueTemplates->getModels()): ?>
        <div class="alert alert-success text-center fw-bold">
            <?= Yii::t('views', 'Завершенных или активных задач на фомирование отчетов нет') ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <?= Yii::t('views', 'Очередь формирования отчетов'); ?>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $queueTemplates,
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
    <?php endif; ?>
<?php endif; ?>

<?php
    Pjax::end();

    $this->registerJs(<<<JS
        setInterval(function(){
            $.pjax.reload({container:'#dashBoard', method: "POST", async: true, push: false});
        }, 20000);
JS);
