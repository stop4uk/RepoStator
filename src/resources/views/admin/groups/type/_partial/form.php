<?php

use yii\bootstrap5\{
    Html,
    ActiveForm
};

use app\widgets\summernote\Summernote;

/**
 * @var \app\modules\users\models\GroupTypeModel $model
 */

$form = ActiveForm::begin([
    'id' => 'grouptype-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'name'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'description')->widget(Summernote::class); ?>
        </div>
    </div>
    <div class="d-grid gap-2 mt-3">
        <?= Html::submitButton(Yii::t('views', $model->isNewEntity ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
    </div>

<?php ActiveForm::end();