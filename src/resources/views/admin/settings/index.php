<?php

use yii\bootstrap5\{
    Html,
    ActiveForm
};

/**
 * @var \app\components\settings\SettingModel $model
 */

$this->title = Yii::t('views', 'Настройки');

$form = ActiveForm::begin([
    'id' => 'settings-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]);

    echo $form->field($model, 'key[]')->hiddenInput()->label(false);

?>

<?php foreach ($model->settingsInArray as $category => $items): ?>
    <div class="card">
        <div class="card-header">
            <?= Yii::t('views', 'Категория: <strong>{name}</strong>', ['name' => $category]); ?>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($items as $key => $inData): ?>
                    <div class="col-12 col-md-4 col-lg-3">
                        <?php
                            $keyName = $category . '__' . $key;
                            echo $form->field($model, "key[$keyName]")->hint($inData['description'], ['class' => 'text-justify text-muted small'])->label(false);
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

    <div class="d-grid gap-2">
        <?= Html::submitButton(Yii::t('views', 'Сохранить'), ['class' => 'btn btn-primary']); ?>
    </div>

<?php
    ActiveForm::end();