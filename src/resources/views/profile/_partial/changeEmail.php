<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/**
 * @var \forms\user\UserEmailChangeForm $userEmailChangeForm
 */

$form = ActiveForm::begin([
    'id' => 'changeemail-form',
    'action' => Url::to(['changeemail']),
    'enableAjaxValidation' => true,
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'validateOnSubmit' => true,
]);
    echo $form->field($userEmailChangeForm, 'email')->textInput(['type' => 'email']);
    echo Html::tag('div', Html::submitButton(Yii::t('views', 'Подать заявку'), ['class' => 'btn btn-lg btn-primary']), ['class' => 'd-grid gap-2 mt-3']);
ActiveForm::end();


