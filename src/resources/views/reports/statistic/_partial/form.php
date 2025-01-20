<?php

use yii\helpers\{
    Url,
    Json
};
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;

use app\modules\reports\entities\ReportFormTemplateEntity;
use app\helpers\CommonHelper;

/**
 * @var \yii\web\View $this
 * @var \app\modules\reports\forms\StatisticForm $model
 */

$templateListField = Html::getInputId($model, 'template');
$periodField = Html::getInputId($model, 'period');

$form = ActiveForm::begin([
    'id' => 'formtemplate-form',
    'action' => Url::to(['form']),
    'enableAjaxValidation' => true,
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'validateOnSubmit' => true,
]);

?>
    <div class="row">
        <div class="col-4">
            <?= $form->field($model, 'report')->widget(Select2::class, [
                'data' => $model->reports,
                'options' => ['placeholder' => '', 'multiple' => false],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    'select2:select' => 'function(e) { getTemplatesList(e.params.data.id); }',
                    'select2:unselect' => "function() {
                        $('#$templateListField').val('').attr({'disabled': true, 'readonly': true}).find('option').each(function() {
                            $(this).remove();
                        }).trigger('change');    
                    }"
                ],
            ]); ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'template')->widget(Select2::class, [
                'data' => [],
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                    'readonly' => true,
                    'disabled' => true
                ],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    'select2:select' => 'function(e) { getPeriodSettings(e.params.data.id); }',
                    'select2:unselect' => "function() {
                        $('#$periodField').val('').trigger('change').attr({'disabled': true, 'readonly': true});
                        $('#periodHint').html('');  
                    }"
                ],
            ]); ?>
        </div>
        <div class="col-4">
            <?php
                $format = str_replace('php:', '', Yii::$app->settings->get('system', 'app_language_date'));

                echo $form->field($model, 'period')->widget(DateRangePicker::class, [
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => ['format' => $format],
                        'timePicker' => false,
                        'showDropdowns' => true,
                        'maxDate' => Yii::$app->formatter->asDate(time()),
                        'ranges' => CommonHelper::getRangesForDate(),
                        'linkedCalendars' => false,
                    ],
                    'options' => [
                        'placeholder' => Yii::$app->formatter->asDate(strtotime(date('Y-01-01'))) . ' - ' . Yii::$app->formatter->asDate(time()),
                        'readonly' => true,
                        'disabled' => true
                    ]
                ])->hint('&nbsp;', ['class' => 'form-text text-danger text-justify', 'id' => 'periodHint']);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-grid gap-2">
                <?= Html::submitButton(Yii::t('views', 'Сформировать'), ['class' => 'btn btn-lg btn-primary']); ?>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();

$urlForTemplatesList = Url::to(['gettemplates', 'report_id' => '']);
$urlForPeriodSettings = Url::to(['getperiod', 'template_id' => '']);
$periodTypes = Json::encode([
    'week' => ReportFormTemplateEntity::REPORT_DATETIME_WEEK,
    'month' => ReportFormTemplateEntity::REPORT_DATETIME_MONTH
]);
$periodHintMessage = Json::encode([
    'week' => Yii::t('views', 'Расчет будет произведен за НЕДЕЛЮ, в которую попадает КРАЙНЯЯ выбранная дата периода, с учетом ее как максимальной'),
    'month' => Yii::t('views', 'Расчет будет произведен за МЕСЯЦ, в который попадает КРАЙНЯЯ выбранная дата периода, с учетом ее как максимальной')
]);


$this->registerJs(<<<JS
    function getTemplatesList(report_id)
    {
        $.ajax({
            url: "$urlForTemplatesList" + report_id,
            beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
            complete: function(xhr, textStatus) {
                $("#hidescreen, #loadingData").fadeOut(10);
                if ( xhr.status == 403 ) {
                    generateToast('error', langMessages.forbiddenTemplate);
                }
            },
            success: function (data) {
                if ( data.elements ) {
                    $.each(data.elements, function(index, value) {
                        let appendOption = new Option(value, index, false, false);
                        $("#$templateListField").append(appendOption);    
                    });    
                
                    $("#$templateListField").attr({"disabled": false, "readonly": false}).trigger('change');
                }
            },
        });    
    }
    
    function getPeriodSettings(template_id)
    {
        let periods = $periodTypes,
            hintsMessage = $periodHintMessage;
        
        $.ajax({
            url: "$urlForPeriodSettings" + template_id,
            beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
            complete: function(xhr, textStatus) {
                $("#hidescreen, #loadingData").fadeOut(10);
                if ( xhr.status == 403 ) {
                    generateToast('error', langMessages.forbiddenTemplate);
                }
            },
            success: function (data) {
                $("#periodHint").html("");
                $("#$periodField").attr({"disabled": false, "readonly": false});
                    
                if ( !data.form_datetime ) {
                    $("#periodHint").html(hintsMessage["week"]);
                } 
                    
                if ( data.form_datetime == periods["month"] ) {
                    $("#periodHint").html(hintsMessage["month"]);
                }    
            },
        });    
    }
JS);