<?php

use kartik\select2\Select2;
use yii\bootstrap5\{
    ActiveForm,
    Html
};

use app\widgets\summernote\Summernote;

/**
 * @var \app\modules\reports\models\ReportModel $model
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
        <div class="col-12 col-xxl-6">
            <?= $form->field($model, 'name')->hint($form->field($model, 'allow_dynamicForm')->checkbox()); ?>
        </div>
        <div class="col-12 col-md-6 col-xxl-3">
            <?= $form->field($model, 'left_period')->textInput([
                'type' => 'number',
                'min' => 1,
            ])->hint(Yii::t('models', 'Перерыв между передачей отчета в минутах'), ['class' => 'form-text text-muted text-justify']); ?>
        </div>
        <div class="col-12 col-md-6 col-xxl-3">
            <?= $form->field($model, 'block_minutes')
                ->input('number', ['min' => 1])
                ->hint(Yii::t('models', 'Время до окончания периода, в которое будет заблокирована передача отчета'), ['class' => 'form-text text-muted text-justify']);
            ?>
        </div>
        <div class="col-12">
            <?= $form
                ->field($model, 'null_day')
                ->checkbox([
                    'disabled' => ($model->getIsNewEntity() || !$model->left_period ),
                    'readonly' => ($model->getIsNewEntity() || !$model->left_period )
                ])
                ->hint(Yii::t('models', 'Если, стоит отметка, то расчетный период, который устанавливается ограничением в ' .
                'минутах, начинает рассчитываться с суток <strong>создания</strong> отчета, а не с момента ее включения/выключения. При этом, ' .
                'если, обнуление расчетного периода выключено, а ограничение стоит, начало расчета будет осуществляться с округления даты ' .
                'создания отчета до часа создания отчета. Например, <strong>при ВКЛЮЧЕННОЙ настройке и времени создания отчета как 01.01.2024 15:33, ' .
                'время расчета будет начинаться с 01.01.2024 00:00, а при ВЫКЛЮЧЕННОЙ настройке, с 15:00</strong>'), ['class' => 'form-text text-justify']);
            ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'description')->widget(Summernote::class); ?>
        </div>
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
        <div class="col-12">
            <?= Html::submitButton(Yii::t('views', $model->getIsNewEntity() ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary w-100 mb-2']); ?>
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