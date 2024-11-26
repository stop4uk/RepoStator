<?php

use mihaildev\ckeditor\CKEditor;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/**
 * @var \models\group\GroupTypeModel $model
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
            <?= $form->field($model, 'description')->widget(CKEditor::class, [
                'editorOptions' => [
                    'toolbarGroups' => [
                        ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
                        ['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
                        ['name' => 'clipboard', 'groups' => ['undo', 'selection', 'clipboard']],
                    ],
                ],
            ]); ?>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 mb-2 d-grid">
            <?= Html::submitButton(Yii::t('views', $model->isNewEntity ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

<?php ActiveForm::end();