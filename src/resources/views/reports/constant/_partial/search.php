<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;

use app\helpers\CommonHelper;

/**
 * @var \app\modules\reports\search\ConstantSearch $searchModel
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
            <div class="col-12 col-md-4">
                <?= $form->field($searchModel, 'record'); ?>
            </div>
            <div class="col-12 col-md-4">
                <?= $form->field($searchModel, 'name'); ?>
            </div>
            <div class="col-12 col-md-4">
                <?= $form->field($searchModel, 'name_full'); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <?= $form->field($searchModel, 'union_rules'); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <?= $form->field($searchModel, 'limitReport')->widget(Select2::class, [
                    'data' => $searchModel->reports,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-6 col-xl-2">
                <label class="form-label d-none d-xl-block">&nbsp;</label>
                <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark w-100']) ?>
            </div>
            <div class="col-6 col-xl-2">
                <label class="form-label d-none d-xl-block">&nbsp;</label>
                <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger w-100']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>