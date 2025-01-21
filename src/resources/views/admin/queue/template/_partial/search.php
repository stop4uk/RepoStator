<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;

use app\helpers\CommonHelper;
use app\modules\reports\helpers\JobHelper;

/**
 * @var \app\modules\reports\search\JobSearch $searchModel
 */

$resource = Url::to(['/admin/queue/template']);

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
            <div class="col-12 col-md-3 col-xxl-2">
                <?= $form->field($searchModel, 'job_status')->dropDownList(CommonHelper::getFilterReplaceData(JobHelper::statuses()), ['prompt' => Yii::t('views', 'Выберите')]); ?>
            </div>
            <div class="col-12 col-md-4 col-xxl-3">
                <?= $form->field($searchModel, 'report_id')->widget(Select2::class, [
                    'data' => $searchModel->reports,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-5 col-xxl-3">
                <?= $form->field($searchModel, 'template_id')->widget(Select2::class, [
                    'data' => $searchModel->templates,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-12 col-xl-4">
                <?= $form->field($searchModel, 'created_gid')->widget(Select2::class, [
                    'data' => $searchModel->groups,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
                <?= $form->field($searchModel, 'created_at')->widget(DateRangePicker::class, [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => false,
                        'locale' => [
                            'format' => 'd.m.Y H:i'
                        ]
                    ]
                ]); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
                <?= $form->field($searchModel, 'updated_at')->widget(DateRangePicker::class, [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => false,
                        'locale' => [
                            'format' => 'd.m.Y H:i'
                        ]
                    ]
                ]); ?>
            </div>
            <div class="col-6 col-xxl-3">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-xxl-block">&nbsp;</label>
                    <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark']) ?>
                </div>
            </div>
            <div class="col-6 col-xxl-3">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-xxl-block">&nbsp;</label>
                    <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>