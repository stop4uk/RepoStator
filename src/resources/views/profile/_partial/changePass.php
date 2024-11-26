<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/**
 * @var \forms\user\UserPasswordChangeForm $userPasswordChangeForm
 */

$form = ActiveForm::begin([
    'id' => 'changepassword-form',
    'action' => Url::to(['changepassword']),
    'enableAjaxValidation' => true,
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'validateOnSubmit' => true,
]);
?>
<div class="row">
    <div class="col-6">
        <?= $form->field($userPasswordChangeForm, 'password')->passwordInput(); ?>
    </div>
    <div class="col-6">
        <?= $form->field($userPasswordChangeForm, 'verifyPassword')->passwordInput(); ?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="d-grid gap-2">
            <?= Html::submitButton(Yii::t('views', 'Сменить пароль'), ['class' => 'btn btn-lg btn-primary']); ?>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
