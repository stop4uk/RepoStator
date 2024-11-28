<?php

use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

/**
 * @var \app\useCases\reports\models\ReportModel $model
 * @var \yii\web\View $this
 */

$form = ActiveForm::begin([
    'id' => 'report-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>

    <div class="row">
        <div class="col-12 col-xl-6">
            <?= $form->field($model, 'name'); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <?= $form->field($model, 'left_period')->textInput([
                'type' => 'number',
                'min' => 1,
            ])->hint(Yii::t('models', 'Перерыв между передачей отчета в минутах'), ['class' => 'form-text text-muted text-justify']); ?>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <?= $form->field($model, 'block_minutes')
                ->input('number', ['min' => 1])
                ->hint(Yii::t('models', 'Время до окончания периода, в которое будет заблокирована передача отчета'), ['class' => 'form-text text-muted text-justify']);
            ?>
        </div>
        <div class="col-12">
            <?= $form
                ->field($model, 'null_day')
                ->checkbox([
                    'disabled' => ( $model->getIsNewEntity() || !$model->left_period ),
                    'readonly' => ( $model->getIsNewEntity() || !$model->left_period )
                ])
                ->hint(Yii::t('models', 'Если, стоит отметка, то расчетный период, который устанавливается ограничением в ' .
                'минутах, начинает рассчитываться с суток <strong>создания</strong> отчета, а не с момента ее включения/выключения. При этом, ' .
                'если, обнуление расчетного периода выключено, а ограничение стоит, начало расчета будет осуществляться с округления даты ' .
                'создания отчета до часа создания отчета. Например, <strong>при ВКЛЮЧЕННОЙ настройке и времени создания отчета как 01.01.2024 15:33, ' .
                'время расчета будет начинаться с 01.01.2024 00:00, а при ВЫКЛЮЧЕННОЙ настройке, с 15:00</strong>'), ['class' => 'form-text text-justify']);
            ?>
        </strong>
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
        <div class="col-12">
            <?= $form->field($model, 'groups_only')->widget(Select2::class, [
                'data' => $model->groupsCanSent,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true,
                ],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'groups_required')->widget(Select2::class, [
                'data' => $model->groupsCanSent,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true,
                ],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 mb-2 d-grid">
            <?= Html::submitButton(Yii::t('views', $model->getIsNewEntity() ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
<?php

ActiveForm::end();

$bindEventField = Html::getInputId($model, 'left_period');
$reactEventField = Html::getInputId($model, 'null_day');
$this->registerJs(<<<JS
    $("#$bindEventField").on("change", function(){
        let state = ( $(this).val() ) ? false : true;
        $("#$reactEventField").attr({"readonly": state, "disabled": state});
    });
JS);