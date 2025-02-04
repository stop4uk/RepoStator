<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;

use app\helpers\CommonHelper;
use app\modules\users\helpers\UserHelper;

/**
 * @var \app\search\UserSearch $searchModel
 */

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
            <div class="col-12 col-md-5 col-xl-6 col-xxl-2">
                <?= $form->field($searchModel, 'name'); ?>
            </div>
            <div class="col-12 col-md-4 col-xl-3 col-xxl-2">
                <?= $form->field($searchModel, 'account_status')->dropDownList(CommonHelper::getFilterReplaceData(UserHelper::statuses()), ['prompt' => Yii::t('views', 'Выберите')]); ?>
            </div>
            <div class="col-12 col-xl-6 col-xxl-2">
                <?= $form->field($searchModel, 'hasGroup')->widget(Select2::class, [
                    'data' => $searchModel->groups,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-6 col-xl-3 col-xxl-2">
                <label class="form-label d-none d-xl-block d-xxl-block">&nbsp;</label>
                <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark w-100']) ?>
            </div>
            <div class="col-6 col-xl-3 col-xxl-2">
                <label class="form-label d-none d-xl-block d-xxl-block">&nbsp;</label>
                <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger w-100']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>