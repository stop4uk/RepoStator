<?php

use yii\helpers\Json;
use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;

use app\helpers\user\UserHelper;

/**
 * @var \app\models\user\UserModel $model
 */

$form = ActiveForm::begin([
    'id' => 'user-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>

    <div class="row">
        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <?= $form->field($model, 'email'); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <?= $form->field($model, 'password')->passwordInput(); ?>
        </div>

        <hr />

        <div class="col-12 col-md-5 col-xl-6 col-xxl-4">
            <?= $form->field($model, 'lastname'); ?>
        </div>
        <div class="col-12 col-md-4 col-xl-3 col-xxl-3">
            <?= $form->field($model, 'firstname'); ?>
        </div>
        <div class="col-12 col-md-3 col-xl-3 col-xxl-3">
            <?= $form->field($model, 'middlename'); ?>
        </div>
        <div class="col-12 col-md-3 col-xl-3 col-xxl-2">
            <?= $form->field($model, 'phone', ['template' => '
                <label class="form-label mb-0" for="pos_phone">{label}</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">+7</span>
                        {input}
                        {error}
                    </div>
            ', 'inputOptions' => ['class' => 'rounded-0 rounded-end form-control']])->textInput(['minlength' => 10, 'maxlength' => 10]); ?>
        </div>
        <div class="col-12 col-md-3 col-xl-3 col-xxl-2">
            <?= $form->field($model, 'account_status')->dropDownList(UserHelper::statuses($model->isNewEntity)); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-6 col-xxl-4">
            <?= $form->field($model, 'group')->widget(Select2::class, [
                'data' => $model->groups,
                'options' => ['placeholder' => '', 'multiple' => false],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>


        <?php if ( Yii::$app->getUser()->can('admin') ): ?>
            <div class="col-12">
                <?= $form->field($model, 'rights')->widget(Select2::class, [
                    'data' => $model->allowRights,
                    'options' => [
                        'placeholder' => '',
                        'multiple' => true,
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
        <?php endif; ?>
        <div class="col-12">
            <?= Html::submitButton(Yii::t('views', $model->isNewEntity ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary w-100']) ?>
        </div>
    </div>
<?php
ActiveForm::end();
