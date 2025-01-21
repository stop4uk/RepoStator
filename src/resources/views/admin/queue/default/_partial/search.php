<?php

use app\helpers\CommonHelper;
use kartik\daterange\DateRangePicker;
use yii\bootstrap5\{ActiveForm, Html};
use yii\helpers\Url;

/**
 * @var \app\modules\admin\search\QueueSearch $searchModel
 */

$resource = Url::to(['/admin/queue']);

?>

<div class="card <?= CommonHelper::getDataShowAttribute($searchModel) ? '' : 'd-none'; ?>" id="searchCard" data-show="<?= CommonHelper::getDataShowAttribute($searchModel); ?>">
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'searchForm',
            'options' => [
                'data-pjax' => true,
                'autocomplete' => 'off'
            ]
        ]); ?>
        <div class="row">
            <div class="col-12 col-md-2 col-xxl-1">
                <?= $form->field($searchModel, 'id')->input('number'); ?>
            </div>
            <div class="col-12 col-md-3 col-xl-5 col-xxl-2">
                <?= $form->field($searchModel, 'channel'); ?>
            </div>
            <div class="col-12 col-md-7 col-xl-5 col-xxl-3">
                <?= $form->field($searchModel, 'job'); ?>
            </div>
            <div class="col-12 col-md-6 col-xxl-3">
                <?= $form->field($searchModel, 'pushed_at')->widget(DateRangePicker::class, [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => false,
                        'locale' => [
                            'format' => 'd.m.Y H:i'
                        ]
                    ]
                ]); ?>
            </div>
            <div class="col-12 col-md-6 col-xxl-3">
                <?= $form->field($searchModel, 'done_at')->widget(DateRangePicker::class, [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => false,
                        'locale' => [
                            'format' => 'd.m.Y H:i'
                        ]
                    ]
                ]); ?>
            </div>
            <div class="col-6">
                <div class="d-grid gap-2">
                    <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark']) ?>
                </div>
            </div>
            <div class="col-6">
                <div class="d-grid gap-2">
                    <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>