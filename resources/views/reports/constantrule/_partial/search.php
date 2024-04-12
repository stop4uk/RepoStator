<?php

/**
 * @var \app\search\report\ConstantruleSearch $searchModel
 */

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;

use app\helpers\CommonHelper;

$resource = Url::to(['/reports/constantrule']);

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
            <div class="col-12 col-md-3 col-xl-4">
                <?= $form->field($searchModel, 'record'); ?>
            </div>
            <div class="col-12 col-md-5 col-xl-4">
                <?= $form->field($searchModel, 'name'); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-4">
                <?= $form->field($searchModel, 'hasConstant')->widget(Select2::class, [
                    'data' => $searchModel->constants,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <?= $form->field($searchModel, 'limitReport')->widget(Select2::class, [
                    'data' => $searchModel->reports,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ])->hint(Yii::t('models', 'Выберите отчет для которого должно работать правило'), ['class' => 'form-text text-justify']); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-4">
                <?= $form->field($searchModel, 'limitGroup')->widget(Select2::class, [
                    'data' => $searchModel->groups,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ])->hint(Yii::t('models', 'Выберите группу, которой ограничена выборка'), ['class' => 'form-text text-justify']); ?>
            </div>
            <div class="col-6 col-xl-2">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-xl-block">&nbsp;</label>
                    <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark']) ?>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-xl-block">&nbsp;</label>
                    <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>