<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/**
 * @var \app\forms\auth\SignupForm $model
 */
?>

<div class="row">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
        <div class="d-table-cell align-middle">
            <div class="text-center mt-4">
                <h2 class="h2"><?= Yii::$app->settings->get('system', 'app_name'); ?>. <span class="text-muted smaller"><?= Yii::t('views', 'Регистрация'); ?></span></h2>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="m-1 mb-0">
                        <?php $form = ActiveForm::begin([
                            'id' => 'signup-form',
                            'enableAjaxValidation' => true,
                            'validateOnBlur' => false,
                            'validateOnChange' => false,
                            'validateOnSubmit' => true,
                            'fieldConfig' => [
                                'errorOptions' => [
                                    'encode' => false,
                                ],
                            ],
                        ]); ?>

                        <div class="row">
                            <div class="col-7">
                                <?= $form->field($model, 'lastname'); ?>
                            </div>
                            <div class="col-5">
                                <?= $form->field($model, 'firstname'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?= $form->field($model, 'middlename'); ?>
                            </div>
                            <div class="col-6">
                                <?= $form->field($model, 'phone', ['template' => '
                                    <label class="form-label mb-0" for="pos_phone">{label}</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">+7</span>
                                            {input}
                                            {error}
                                        </div>
                                    ', 'inputOptions' => ['class' => 'rounded-0 rounded-end form-control']
                                ])->textInput(['minlength' => 10, 'maxlength' => 10]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <?php
                                    echo $form->field($model, 'email')->input('email');
                                    echo $form->field($model, 'password')->passwordInput();
                                ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-2">
                            <?= Html::submitButton(Yii::t('views', 'Зарегистрироваться'), ['class' => 'btn btn-lg btn-primary']); ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>

            <div class="row d-flex justify-content-center mt-3">
                <hr class="w-50" />
            </div>
            <p class="text-center">
                <?php
                    echo Yii::t('views' ,'{link} или ', [
                        'link' => Html::a(Yii::t('views', 'Авторизоваться'), ['/login'])
                    ]);

                    echo Html::a(Yii::t('views', 'Восстановить пароль'), ['/recovery']);
                ?>
            </p>
        </div>
    </div>
</div>
