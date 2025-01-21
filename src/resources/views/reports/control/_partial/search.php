<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;

use app\helpers\CommonHelper;

/**
 * @var \app\modules\reports\search\ConstantSearch $searchModel
 */

$resource = Url::to(["/{$this->context->id}"]);
$format = str_replace('php:', '', Yii::$app->settings->get('system', 'app_language_date'));

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
            <div class="col-12 col-md-6 col-xl-4">
                <?= $form->field($searchModel, 'report_id')->widget(Select2::class, [
                    'data' => $searchModel->reports,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <?= $form->field($searchModel, 'struct_id')->widget(Select2::class, [
                    'data' => $searchModel->structures,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-7 col-xl-4">
                <?= $form->field($searchModel, 'group_id')->widget(Select2::class, [
                    'data' => $searchModel->groups,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-5 col-xl-3">
                <?= $form->field($searchModel, 'created_uid')->widget(Select2::class, [
                    'data' => $searchModel->usersAllow,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3">
                <?= $form->field($searchModel, 'hasConstant')->widget(Select2::class, [
                    'data' => $searchModel->constants,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3">
                <?= $form->field($searchModel, 'report_datetime')->widget(DateRangePicker::class, [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => ['format' => $format],
                        'timePicker' => false,
                        'showDropdowns' => true,
                        'maxDate' => Yii::$app->formatter->asDate(time()),
                        'ranges' => CommonHelper::getRangesForDate(),
                        'linkedCalendars' => false,
                    ],
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(time()) . ' - ' . Yii::$app->formatter->asDate(time()),
                    ]
                ]); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3">
                <?= $form->field($searchModel, 'created_at')->widget(DateRangePicker::class, [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => ['format' => $format],
                        'timePicker' => false,
                        'showDropdowns' => true,
                        'maxDate' => Yii::$app->formatter->asDate(time()),
                        'ranges' => CommonHelper::getRangesForDate(),
                        'linkedCalendars' => false,
                    ],
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(time()) . ' - ' . Yii::$app->formatter->asDate(time()),
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