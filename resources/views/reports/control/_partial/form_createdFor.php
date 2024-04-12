<?php

/**
 * @var \yii\web\View $this
 * @var \app\forms\report\ControlCreateForForm $model
 * @var array $reports
 */

use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\select2\Select2;

use app\forms\report\ControlCreateForForm;

$model = new ControlCreateForForm(['reports' => $reports]);
$fieldPeriodInput = Html::getInputId($model, 'period');

$form = ActiveForm::begin([
    'id' => 'createfor-form',
    'action' => Url::to(['createfor']),
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]);
?>
    <div class="row">
        <div class="col-4">
            <?= $form->field($model, 'group')->widget(Select2::class, [
                'data' => $model->groups,
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                ],
            ]); ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'report')->widget(Select2::class, [
                'data' => $model->reports,
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                ],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    'select2:select' => 'function(e) { getPeriods(e); }',
                    'select2:unselect' => "function() {
                        $('#resultBlock, #resultBlockContent, #resultBlockError').addClass('d-none');
                        $('#resultBlockContentList').html('');
                     
                        $('#$fieldPeriodInput').val('').attr({'readonly': true, 'disabled': true}).find('option').each(function() {
                            $(this).remove();
                        }).trigger('change');
                    }",
                ]
            ]); ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'period')->widget(Select2::class, [
                'data' => [],
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                    'disabled' => true,
                    'readonly' => true
                ],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>
        <div class="col-12">
            <div class="d-grid gap-2">
                <?= Html::submitButton(Yii::t('views', 'Передать'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>
<?php

ActiveForm::end();

$urlForGetPeriod = Url::to(['getperiods', 'report_id' => '']);
$this->registerJs(<<< JS
    function getPeriods(e) {
        let selectId = e.params.data.id;
        
        if ( selectId ) {
            $("#resultBlock, #resultBlockContent, #resultBlockError").addClass("d-none");
            $("#resultBlockContentList").html("");
            
            $('#$fieldPeriodInput').val('').attr({'readonly': true, 'disabled': true}).find('option').each(function() {
                $(this).remove();
            }).trigger('change');
            
            $.ajax({
                url: "$urlForGetPeriod" + selectId,
                beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
                complete: function(xhr, textStatus) {
                    $("#hidescreen, #loadingData").fadeOut(10);
                    if ( xhr.status == 403 ) {
                        generateToast('error', langMessages.forbiddenTemplate);
                    }
                },
                success: function (data) {
                    if ( data.items ) {
                        $.each(data.items, function(index, value) {
                            let appendOption = new Option(value, value, false, false);
                            $("#$fieldPeriodInput").append(appendOption);    
                        });
                    
                        $("#$fieldPeriodInput").attr({"disabled": false, "readonly": false}).trigger('change');
                    }
                },
            });
        }
    }
JS);
