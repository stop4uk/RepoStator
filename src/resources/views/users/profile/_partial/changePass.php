<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};

/**
 * @var \app\modules\users\forms\user\UserPasswordChangeForm $userPasswordChangeForm
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
    <div class="col-12 col-md-6">
        <?= $form->field($userPasswordChangeForm, 'password')->passwordInput(); ?>
    </div>
    <div class="col-12 col-md-6">
        <?= $form->field($userPasswordChangeForm, 'verifyPassword')->passwordInput(); ?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?= Html::submitButton(Yii::t('views', 'Сменить пароль'), ['class' => 'btn btn-lg btn-primary w-100']); ?>
    </div>
</div>
<?php
ActiveForm::end();
