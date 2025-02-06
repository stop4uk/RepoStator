<?php

use yii\bootstrap5\{
    ActiveForm,
    Html
};

/**
 * @var \app\modules\users\forms\auth\RecoveryForm $model
 */

?>

<div class="row">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
        <div class="d-table-cell align-middle">
            <div class="text-center mt-4">
                <h2 class="h2"><?= Yii::$app->settings->get('system', 'app_name'); ?>. <span class="text-muted smaller"><?= Yii::t('views', 'Восстановление'); ?></span></h2>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="m-1 mb-0">
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'recovery-process-form',
                            'enableAjaxValidation' => true,
                            'validateOnBlur' => false,
                            'validateOnChange' => false,
                            'validateOnSubmit' => true,
                        ]);
                            echo $form->field($model, 'password')->input('password');
                            echo $form->field($model, 'verifyPassword')->input('password');
                            echo Html::tag('div', Html::submitButton(Yii::t('views', 'Обновить пароль'), ['class' => 'btn btn-lg btn-primary']), ['class' => 'd-grid gap-2 mt-2']);
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>

            <div class="row d-flex justify-content-center mt-3">
                <hr class="w-50" />
            </div>
            <p class="text-center">
                <?= Html::a(Yii::t('views', 'Авторизоваться'), ['/login']); ?>
            </p>
        </div>
    </div>
</div>
