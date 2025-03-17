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

use app\helpers\CommonHelper;
use app\modules\reports\{
    entities\ReportFormTemplateEntity,
    helpers\TemplateHelper
};

/**
 * @var \yii\web\View $this
 * @var \app\modules\reports\forms\StatisticForm $model
 */

$urlForTemplatesList = Url::to(['getformsettings', 'report_id' => '']);
$urlForPeriodSettings = Url::to(['getperiod', 'template_id' => '']);
$urlForDynamicSettings = Url::to(['getdynamicformsettings', 'report_id' => '']);
$periodTypes = Json::encode([
    'week' => ReportFormTemplateEntity::REPORT_DATETIME_WEEK,
    'month' => ReportFormTemplateEntity::REPORT_DATETIME_MONTH
]);
$periodHintMessage = Json::encode([
    'week' => Yii::t('views', 'Расчет будет произведен за НЕДЕЛЮ, в которую попадает КРАЙНЯЯ выбранная дата периода, с учетом ее как максимальной'),
    'month' => Yii::t('views', 'Расчет будет произведен за МЕСЯЦ, в который попадает КРАЙНЯЯ выбранная дата периода, с учетом ее как максимальной')
]);
$formFieldIds = [
    'reportID' => Html::getInputId($model, 'report'),
    'template' => Html::getInputId($model, 'template'),
    'period' => Html::getInputId($model, 'period'),
    'dynamic_type' => Html::getInputId($model, 'dynamic_form_type'),
    'dynamic_column' => Html::getInputId($model, 'dynamic_form_column'),
    'dynamic_row' => Html::getInputId($model, 'dynamic_form_row'),
    'dynamic_use_grouptype' => Html::getInputId($model, 'dynamic_use_grouptype'),
];
$typeColumn = ReportFormTemplateEntity::REPORT_TABLE_TYPE_CONST;


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
        <div class="col-12 col-md-4">
            <?= $form->field($model, 'report')->widget(Select2::class, [
                'data' => $model->reports,
                'options' => ['placeholder' => '', 'multiple' => false],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    'select2:select' => 'function(e) {
                        clearTemplateListAndPeriodSettings(); 
                        getTemplatesList(e.params.data.id); 
                    }',
                    'select2:unselect' => "clearTemplateListAndPeriodSettings"
                ],
            ]); ?>
        </div>
        <div class="col-12 col-md-4">
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
                        $('#{$formFieldIds['period']}').attr({'disabled': true, 'readonly': true});
                        $('#periodHint').html('');  

                        if (dynamicSettings['allow'] && dynamicSettings['id'] == $('#{$formFieldIds['reportID']}')) {
                            $('#dynamicFormSettings').removeClass('d-none');
                            $('#{$formFieldIds['period']}').attr({'disabled': false, 'readonly': false});
                        }
                    }"
                ],
            ]); ?>
        </div>
        <div class="col-12 col-md-4">
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
                        'autoApply' => true,
                        'autoUpdateInput' => true,
                    ],
                    'options' => [
                        'value' => Yii::$app->formatter->asDate(strtotime(date('Y-01-01'))) . ' - ' . Yii::$app->formatter->asDate(time()),
                        'readonly' => true,
                        'disabled' => true
                    ]
                ])->hint('&nbsp;', ['class' => 'form-text text-danger text-justify', 'id' => 'periodHint']);
            ?>
        </div>
    </div>

    <div id="dynamicFormSettings" class="d-none">
        <hr />
        <div class="row">
            <div class="col-12 col-lg-3">
                <?= $form->field($model, 'dynamic_form_type')->dropDownList(TemplateHelper::getTableTypes(), ['prompt' => Yii::t('views', 'Выберите')]); ?>
            </div>
            <div class="col-12 col-lg-3">
                <?= Html::label('&nbsp;', '', ['class' => 'form-label d-none d-lg-block']); ?>
                <?= $form->field($model, 'dynamic_use_appg')->checkbox(); ?>
            </div>
            <div class="col-12 col-lg-3">
                <?= Html::label('&nbsp;', '', ['class' => 'form-label d-none d-lg-block']); ?>
                <?= $form->field($model, 'dynamic_use_grouptype')->checkbox(['disabled' => true, 'readonly' => true]); ?>
            </div>
            <div class="col-12 col-lg-3">
                <?= Html::label('&nbsp;', '', ['class' => 'form-label d-none d-lg-block']); ?>
                <?= $form->field($model, 'dynamic_use_jobs')->checkbox(); ?>
            </div>
            <div class="col-12 col-lg-6">
                <?= $form->field($model, 'dynamic_form_column')->widget(Select2::class, [
                    'data' => [],
                    'options' => [
                        'placeholder' => '',
                        'multiple' => true,
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-lg-6">
                <?= $form->field($model, 'dynamic_form_row')->widget(Select2::class, [
                    'data' => [],
                    'options' => [
                        'placeholder' => '',
                        'multiple' => true,
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2">
        <?= Html::submitButton(Yii::t('views', 'Сформировать'), ['class' => 'btn btn-lg btn-primary w-100']); ?>
    </div>
<?php
ActiveForm::end();

$this->registerJs(<<<JS
    var dynamicSettings = {'allow': false, 'id': null},
        dynamicGroups = [],
        dynamicConstAndRules = [];
JS, $this::POS_BEGIN);

$this->registerJs(<<<JS
    function clearTemplateListAndPeriodSettings()
    {
        dynamicSettings = {'allow': false, 'id': null};
        dynamicGroups, dynamicConstAndRules = [];
        
        $("#dynamicFormSettings").addClass("d-none").find("input:text, select").val("").find("input:checkbox").prop("checked", false);
        $("#{$formFieldIds['dynamic_column']}, {$formFieldIds['dynamic_row']}").find("option").each(function(){ $(this).remove(); });
        
        $("#{$formFieldIds['template']}").val("").trigger("change").attr({'disabled': true, 'readonly': true});
        $("#{$formFieldIds['template']}").find("option").each(function(){ $(this).remove(); });
        
        $('#{$formFieldIds['period']}').attr({'disabled': true, 'readonly': true});
        $('#periodHint').html('');
    }

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
                        $("#{$formFieldIds['template']}").append(appendOption);    
                    });    
                    
                    $("#{$formFieldIds['template']}").attr({"disabled": false, "readonly": false}).val("").trigger('change');
                    if (data.allowDynamic) {
                        $.ajax({
                            url: "$urlForDynamicSettings" + report_id,
                            beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
                            complete: function(xhr, textStatus) {
                                $("#hidescreen, #loadingData").fadeOut(10);
                                if ( xhr.status == 403 ) {
                                    generateToast('error', langMessages.forbiddenTemplate);
                                }
                            },
                            success: function (data) {
                                $("#{$formFieldIds['period']}").attr({"disabled": false, "readonly": false});
                                $("#dynamicFormSettings").removeClass("d-none");
                                
                                dynamicSettings = {'allow': true, 'id': report_id};
                                dynamicGroups = data.groups;
                                dynamicConstAndRules = data.mergeConstantAndRules;
                            }
                        });
                    }
                }
            },
        });    
    }
    
    function getPeriodSettings(template_id)
    {
        let periods = $periodTypes,
            hintsMessage = $periodHintMessage;
     
        $("#dynamicFormSettings").addClass("d-none").find("input:text, select").val("").find("input:checkbox").prop("checked", false);
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
                $("#{$formFieldIds['period']}").attr({"disabled": false, "readonly": false});
                    
                if ( !data.form_datetime ) {
                    $("#periodHint").html(hintsMessage["week"]);
                } 
                    
                if ( data.form_datetime == periods["month"] ) {
                    $("#periodHint").html(hintsMessage["month"]);
                }    
            },
        });    
    }
    
    $("#{$formFieldIds['dynamic_type']}").on("change", function(){
        $("#{$formFieldIds['template']}, #{$formFieldIds['dynamic_row']}, #{$formFieldIds['dynamic_column']}").val("").trigger("change");
        $("#{$formFieldIds['dynamic_column']}, #{$formFieldIds['dynamic_row']}").find("option").each(function(){ $(this).remove(); });
        $("#{$formFieldIds['dynamic_use_grouptype']}").attr({"disabled": true, "readonly": true});
        
        if ($(this).val()) {
            let dataForColumns = ($(this).val() == $typeColumn) ? dynamicConstAndRules : dynamicGroups,
                dataForRows = ($(this).val() == $typeColumn) ? dynamicGroups : dynamicConstAndRules;
            
            $("#{$formFieldIds['template']}").val("").trigger("change").attr({"disabled": true, "readonly": true});
            $.each(dataForColumns, function(index, value) {
                let appendOption = new Option(value, index, false, false);
                $("#{$formFieldIds['dynamic_column']}").append(appendOption);    
            });
                
            $.each(dataForRows, function(index, value) {
                let appendOption = new Option(value, index, false, false);
                $("#{$formFieldIds['dynamic_row']}").append(appendOption);    
            });
            
            if ($(this).val() == $typeColumn) {
                $("#{$formFieldIds['dynamic_use_grouptype']}").attr({"disabled": false, "readonly": false});    
            }
            
            return;
        }
        
        $("#{$formFieldIds['template']}").attr({"disabled": false, "readonly": false});
    });
JS);
