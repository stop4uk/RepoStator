<?php

use yii\bootstrap5\{
    Modal,
    Html,
    ActiveForm
};

/**
 * @var \app\modules\users\models\user\ProfileModel $model
 * @var \app\modules\users\forms\user\UserEmailChangeForm $userEmailChangeForm
 * @var \app\modules\users\forms\user\UserPasswordChangeForm $userPasswordChangeForm
 */

$userCanChangeEmail = Yii::$app->settings->get('auth', 'profile_enableChangeEmail');

$form = ActiveForm::begin([
    'id' => 'profile-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>
    <div class="row">
        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <?= $form->field($model, 'lastname'); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <?= $form->field($model, 'firstname'); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <?= $form->field($model, 'middlename'); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <?= $form->field($model, 'phone', ['template' => '
            <label class="form-label mb-0" for="profile-phone_mobile">{label}</label>
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">+7</span>
                    {input}
                    {error}
            </div>
            ', 'inputOptions' => ['class' => 'rounded-0 rounded-end form-control']
            ]); ?>
        </div>
        <div class="col-12 col-xl-8 col-xxl-12">
            <label class="form-label d-none d-xl-block d-xxl-none">&nbsp</label>
            <?= Html::submitButton(Yii::t('views', 'Обновить'), ['class' => 'btn btn-primary w-100']); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
    <div class="row mt-3">
        <?php if ($userCanChangeEmail ): ?>
            <div class="col-12 mb-2 mb-md-0 col-md-6">
                <?php
                    Modal::begin([
                        'title' => Yii::t('views', 'Смена Email адреса'),
                        'toggleButton' => [
                            'label' => Yii::t('views', 'Сенить Email'),
                            'class' => 'btn btn-dark w-100'
                        ],
                    ]);
                        echo $this->render('changeEmail', ['userEmailChangeForm' => $userEmailChangeForm]);
                    Modal::end();
                ?>
            </div>
        <?php endif; ?>
        <div class="<?= $userCanChangeEmail ? 'col-12 col-md-6' : 'col-12' ?>">
            <?php
                Modal::begin([
                    'title' => Yii::t('views', 'Обновление пароля'),
                    'toggleButton' => [
                        'label' => Yii::t('views', 'Сменить пароль'),
                        'class' => 'btn btn-dark w-100'
                    ],
                ]);
                    echo $this->render('changePass', ['userPasswordChangeForm' => $userPasswordChangeForm]);
                Modal::end();
            ?>
        </div>
    </div>