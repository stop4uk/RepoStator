<?php

use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

/**
 * @var \app\useCases\reports\models\ConstantModel $model
 */

$form = ActiveForm::begin([
    'id' => 'reportconstant-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>

    <div class="row">
        <div class="col-12 col-md-4 col-xl-3">
            <?= $form->field($model, 'record')->hint(Yii::t('models', 'Должно быть уникальным для констант и правил сложения')); ?>
        </div>
        <div class="col-12 col-md-4">
            <?= $form->field($model, 'name'); ?>
        </div>
        <div class="col-12 col-md-4 col-xl-5">
            <?= $form->field($model, 'name_full'); ?>
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

    <div class="row">
        <div class="col-12 col-xl-6">
            <?= $form->field($model, 'union_rules')
                ->hint(Yii::t('models', 'Текстовое правило по принципу ключ=значение. Где клю означает ID блока ' .
                    'объединения, а значение название группы внутри этого блока. Например, <strong>КОАП=Раздел 8</strong> будет определено ' .
                    'как блок "КОАП" и, группа "Раздел 8". При выводе структуры передачи с разрешенными правилами группировки, все константы, ' .
                    'которые имеют такое же название блока в правиле будут оъединены воедино и разделены на мини группы. Причем название ' .
                    'группы будет выведено. <strong>Пробел между названием блока и раздела не допускается. Разделителем служит знак равенства</strong>'),
                    ['class' => 'form-text text-muted text-justify']);
            ?>
        </div>
        <div class="col-12 col-xl-6">
            <?= $form->field($model, 'reports_only')->widget(Select2::class, [
                'data' => $model->reports,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true,
                ],
                'pluginOptions' => ['allowClear' => true],
            ])->hint(Yii::t('models', 'Влияет на попадание в список при формировании структуры передачи'), ['class' => 'form-text text-justify']); ?>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 mb-2 d-grid">
            <?= Html::submitButton(Yii::t('views', $model->getIsNewEntity() ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

<?php ActiveForm::end();