<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;

use app\helpers\CommonHelper;

/**
 * @var \app\modules\reports\search\StructureSearch $searchModel
 */

$resource = Url::to(["/reports/{$this->context->id}"]);

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
            <div class="col-12 col-md-8 col-xl-6 col-xxl-3">
                <?= $form->field($searchModel, 'name'); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3 col-xxl-3">
                <?= $form->field($searchModel, 'report_id')->widget(Select2::class, [
                    'data' => $searchModel->reports,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3 col-xxl-3">
                <?= $form->field($searchModel, 'hasGroup')->widget(Select2::class, [
                    'data' => $searchModel->groups,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-3 col-xl-4 col-xxl-3">
                <?= $form->field($searchModel, 'use_union_rules')
                    ->dropDownList(CommonHelper::getFilterReplaceData(CommonHelper::getDefaultDropdown()), [
                            'prompt' => Yii::t('views', 'Выберите')
                        ]
                    ); ?>
            </div>
            <div class="col-6 col-md-3 col-xl-4 col-xxl-6">
                <label class="form-label d-none d-md-block d-xxl-none">&nbsp;</label>
                <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark w-100']) ?>
            </div>
            <div class="col-6 col-md-2 col-xl-4 col-xxl-6">
                <label class="form-label d-none d-md-block d-xxl-none">&nbsp;</label>
                <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger w-100']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>