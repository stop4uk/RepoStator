<?php

/**
 * @var \app\search\report\ReportSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\helpers\CommonHelper;

$this->title = Yii::t('views', 'Список отчетов');

?>
    <div class="d-flex justify-content-end mb-2">
        <?php
            if ( Yii::$app->getUser()->can('report.create') ) {
                echo Html::a(Yii::t('views', 'Новый отчет'), ['create'], ['class' => 'btn btn-primary pt-1 pb-1 me-2']);
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

<?php Pjax::begin(['id' => 'reportList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>
    <?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>
    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => Yii::t('views', 'Отчеты отсутствут'),
                'emptyTextOptions' => ['class' => 'alert alert-danger text-center fw-bold'],
                'tableOptions' => ['class' => 'table'],
                'columns' => [
                    'name',
                    [
                        'label' => null,
                        'format' => 'html',
                        'value' => function($data) {
                            if ($data->left_period) {
                                $zero = new \DateTime('@0');
                                $offset = new \DateTime('@' . $data->left_period * 60);
                                $diffs = explode('.', $zero->diff($offset)->format('%m.%d.%h.%i'));
                                $resultString = '';

                                $leftPeriod = Yii::t('views', 'Перерыв передачи: ');
                                foreach ($diffs as $key => $value) {
                                    if ( $value ) {
                                        switch($key) {
                                            case 0:
                                            $leftPeriod .= Yii::t('views', '{n, plural, =1{# месяц} one{# месяц} few{# месяца} many{# месяцев} other{# месяцев}}', ['n' => $value]);
                                                break;
                                            case 1:
                                                $leftPeriod .= Yii::t('views', '{n, plural, =1{# день} one{# день} few{# дня} many{# дней} other{# дней}}', ['n' => $value]);
                                                break;
                                            case 2:
                                                $leftPeriod .= Yii::t('views', '{n, plural, =1{# час} one{# час} few{# часа} many{# часов} other{# часов}}', ['n' => $value]);
                                                break;
                                            case 3:
                                                $leftPeriod .= Yii::t('views', '{n, plural, =1{# минуту} one{# минуту} few{# минуты} many{# минут} other{# минут}}', ['n' => $value]);
                                                break;
                                        }
                                    }
                                }

                                $resultString .= Html::tag('span', $leftPeriod, ['class' => 'badge bg-primary']) . '&nbsp;';

                                if ( $data->block_minutes ) {
                                    $resultString .= Html::tag(
                                        'span',
                                        Yii::t('views', 'Закрывается за {n, plural, =1{1 минуту} one{# минуту} few{# минуты} many{# минут} other{# минут}}', ['n' => $data->block_minutes]),
                                    ['class' => 'badge bg-danger']);
                                }

                                return $resultString;
                            }
                        }
                    ],
                    [
                        'label' => null,
                        'format' => 'html',
                        'value' => function($data) {
                            $resultString = '';

                            $resultString .= Html::tag('i', '', [
                                'class' => 'bi bi-circle-fill text-' . CommonHelper::getYesOrNoRecordColor($data->record_status),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'bottom',
                                'title' => Yii::t('views', 'Статус записи: {status}', ['status' => CommonHelper::getYesOrNoRecord($data->record_status)])
                            ]);

                            if ( $data->groups_only ) {
                                $resultString .= Html::tag('span', '<i class="bi bi-exclamation-triangle ms-2"></i>', [
                                    'class' => 'text-danger fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Доступен только для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($data->groups_only))]),
                                ]);
                            }

                            if ( $data->groups_required ) {
                                $resultString .= Html::tag('span', '<i class="bi bi-card-checklist ms-2"></i>', [
                                    'class' => 'text-primary fw-bold',
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'title' => Yii::t('views', 'Обязателен для {n, plural, =1{одной группы} one{# группы} few{# групп} many{# групп} other{# групп}}', ['n' => count(CommonHelper::explodeField($data->groups_required))]),
                                ]);
                            }

                            return $resultString;
                        }
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => false,
                        'headerOptions' => ['width' => '10%'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{view} {edit} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="bi bi-eye text-dark me-2"></i>', Url::to(['view', 'id' => $model->id]), ['data-pjax' => 0]);
                            },
                            'edit' => function($url, $model) {
                                return Html::a('<i class="bi bi-pen text-dark me-2"></i>', Url::to(['edit', 'id' => $model->id]), ['data-pjax' => 0]);
                            },
                            'delete' => function($url, $model) {
                                return Html::tag('span', '<i class="bi bi-trash text-dark"></i>', [
                                    'role' => 'button',
                                    'data-message' => Yii::t('views', 'Вы действительно хотите удалить отчет "{name}"?', ['name' =>  $model->name]),
                                    'data-url' => Url::to(['delete', 'id' => $model->id]),
                                    'data-pjaxContainer' => '#reportList',
                                    'onclick' => 'workWithRecord($(this))',
                                ]);
                            }
                        ],
                        'visibleButtons' => [
                            'view' => function($model) {
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);

                                return (
                                    Yii::$app->getUser()->can('report.view.main', $ruleArray)
                                    || Yii::$app->getUser()->can('report.view.group', $ruleArray)
                                    || Yii::$app->getUser()->can('report.view.all', $ruleArray)
                                    || Yii::$app->getUser()->can('report.view.delete.main', $ruleArray)
                                    || Yii::$app->getUser()->can('report.view.delete.group', $ruleArray)
                                    || Yii::$app->getUser()->can('report.view.delete.all', $ruleArray)
                                );
                            },
                            'edit' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);

                                return (
                                    Yii::$app->getUser()->can('report.edit.main', $ruleArray)
                                    || Yii::$app->getUser()->can('report.edit.group', $ruleArray)
                                    || Yii::$app->getUser()->can('report.edit.all', $ruleArray)
                                );
                            },
                            'delete' => function($model){
                                $ruleArray = $model->toArray(['created_uid', 'created_gid', 'record_status']);

                                return (
                                    Yii::$app->getUser()->can('report.delete.main', $ruleArray)
                                    || Yii::$app->getUser()->can('report.delete.group', $ruleArray)
                                    || Yii::$app->getUser()->can('report.delete.all', $ruleArray)
                                );
                            }
                        ]
                    ],
                ]
            ]); ?>
        </div>
    </div>
<?php
    Pjax::end();






