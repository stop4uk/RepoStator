<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    ActiveForm,
    Html
};
use kartik\select2\Select2;

use app\modules\reports\forms\ControlCheckFullForm;

/**
 * @var \yii\web\View $this
 * @var \app\modules\reports\forms\ControlCheckFullForm $model
 * @var array $groups
 * @var array $reports
 */

$model = new ControlCheckFullForm(['reports' => $reports]);
$fieldPeriodInput = Html::getInputId($model, 'period');

$form = ActiveForm::begin([
    'id' => 'checkfull-form',
    'action' => Url::to(['checkfull']),
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]);
?>
    <div class="row">
        <div class="col-12 col-lg-4">
            <?= $form->field($model, 'report')->widget(Select2::class, [
                'data' => $model->reports,
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                ],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    'select2:select' => 'function(e) { getPeriodsForCheckFull(e); }',
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
        <div class="col-12 col-lg-4">
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
        <div class="col-12 col-lg-4">
            <?= Html::label('&nbsp;', '', ['class' => 'form-label d-none d-lg-block']); ?>
            <?= Html::submitButton(Yii::t('views', 'Проверить'), ['class' => 'btn btn-primary w-100']); ?>
        </div>
    </div>

    <div class="row mt-4 d-none" id="resultBlock">
        <hr />
        <div class="col-12 d-none" id="resultBlockContent">
            <h4 class="text-danger еуче-су">
                <?= Yii::t('views', 'Группы, которые не предоставили сведения за отчетный период') ?>
            </h4>
            <ul id="resultBlockContentList"></ul>
        </div>
        <div class="col-12 text-center d-none" id="resultBlockError">
            <h4 class="text-success">
                <?= Yii::t('views', 'Сведения от всех групп переданы, либо не установлены обязательства по передаче'); ?>
            </h4>
        </div>
    </div>
<?php

ActiveForm::end();

$urlForGetPeriod = Url::to(['getperiods', 'report_id' => '']);
$this->registerJs(<<< JS
    $("#checkfull-form").on("beforeSubmit", function(event) {
        event.preventDefault();
        
        let form = $(this);
        $("#resultBlock, #resultBlockContent, #resultBlockError").addClass("d-none");
        $("#resultBlockContentList").html("");
        
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: form.serialize(),
            beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
            complete: function(xhr) {
                $("#hidescreen, #loadingData").fadeOut(10);
                if ( xhr.status == 403 ) {
                    generateToast('error', langMessages.forbiddenTemplate);
                }
            },
            success: function (data) {
                $("#resultBlock").removeClass("d-none");
                if ( data.length ) {
                    $.each(data, function(index, value) {
                        $("#resultBlockContentList").append("<li>" + value + "</li>");
                    });
                    
                    $("#resultBlock, #resultBlockContent").removeClass("d-none");
                } else {
                    $("#resultBlockError").removeClass("d-none");    
                }
            }
        });    
            
        return false;
    });

    function getPeriodsForCheckFull(e) {
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
