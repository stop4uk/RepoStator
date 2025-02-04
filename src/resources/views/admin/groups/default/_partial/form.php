<?php

use yii\bootstrap5\{
    Html,
    ActiveForm
};

use app\helpers\CommonHelper;
use app\widgets\summernote\Summernote;

/**
 * @var \app\modules\users\models\GroupModel $model
 */

$form = ActiveForm::begin([
    'id' => 'group-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>

    <div class="row">
        <div class="col-12 col-md-3 col-xl-2">
            <?= $form->field($model, 'code'); ?>
        </div>
        <div class="col-12 col-md-9 col-xl-4">
            <?= $form->field($model, 'name'); ?>
        </div>
        <div class="col-12 col-md-12 col-xl-6">
            <?= $form->field($model, 'name_full'); ?>
        </div>
        <div class="col-12 col-md-3">
            <?= $form->field($model, 'accept_send')->dropDownList(CommonHelper::getDefaultDropdown(), ['prompt' => Yii::t('views', 'Выберите')]); ?>
        </div>
        <div class="col-12 col-md-9">
            <?= $form->field($model, 'type_id')->dropDownList($model->types, ['prompt' => Yii::t('views', 'Выберите')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'description')->widget(Summernote::class); ?>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 mb-2 d-grid">
            <?= Html::submitButton(Yii::t('views', $model->isNewEntity ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

<?php ActiveForm::end();