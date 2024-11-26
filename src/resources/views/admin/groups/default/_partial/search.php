<?php

use app\helpers\CommonHelper;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/**
 * @var \search\group\GroupSearch $searchModel
 */

$resource = Url::to(['/admin/groups']);

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
            <div class="col-12 col-md-2">
                <?= $form->field($searchModel, 'code'); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-4 col-xxl-3">
                <?= $form->field($searchModel, 'name'); ?>
            </div>
            <div class="col-12 col-md-6 col-xl-6 col-xxl-3">
                <?= $form->field($searchModel, 'name_full'); ?>
            </div>
            <div class="col-12 col-md-3 col-xl-3 col-xxl-2">
                <?= $form->field($searchModel, 'accept_send')->dropDownList(CommonHelper::getFilterReplaceData(CommonHelper::getDefaultDropdown()), ['prompt' => Yii::t('views', 'Выберите')]); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3 col-xxl-2">
                <?= $form->field($searchModel, 'type_id')->dropDownList($searchModel->types, ['prompt' => Yii::t('views', 'Выберите')]); ?>
            </div>
            <div class="col-6 col-md-3 col-xxl-6">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-md-block d-xxl-none">&nbsp;</label>
                    <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark']) ?>
                </div>
            </div>
            <div class="col-6 col-md-2 col-xl-3 col-xxl-6">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-md-block d-xxl-none">&nbsp;</label>
                    <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>