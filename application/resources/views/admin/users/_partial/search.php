<?php

/**
 * @var \app\search\UserSearch $searchModel
 */

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;

use app\helpers\{
    CommonHelper,
    user\UserHelper
};

$resource = Url::to(['/admin/users']);

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
            <div class="col-12 col-md-3 col-xl-3 col-xxl-2">
                <?= $form->field($searchModel, 'email'); ?>
            </div>
            <div class="col-12 col-md-5 col-xl-6 col-xxl-4">
                <?= $form->field($searchModel, 'name'); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3 col-xxl-2">
                <?= $form->field($searchModel, 'account_status')->dropDownList(CommonHelper::getFilterReplaceData(UserHelper::statuses()), ['prompt' => Yii::t('views', 'Выберите')]); ?>
            </div>
            <div class="col-12 col-xl-6 col-xxl-4">
                <?= $form->field($searchModel, 'hasGroup')->widget(Select2::class, [
                    'data' => $searchModel->groups,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-6 col-xl-3 col-xxl-6">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-xl-block d-xxl-none">&nbsp;</label>
                    <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark']) ?>
                </div>
            </div>
            <div class="col-6 col-xl-3 col-xxl-6">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-xl-block d-xxl-none">&nbsp;</label>
                    <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>