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

use app\components\attachedFiles\widgets\attachfile\AttachFileWidget;
use app\helpers\CommonHelper;
use app\modules\reports\{
    entities\ReportFormTemplateEntity,
    helpers\TemplateHelper,
};

/**
 * @var \yii\web\View $this
 * @var \app\modules\reports\models\TemplateModel $model
 * @var bool $canDeleted
 * @var bool $view
 */

$attachedTemplate = $model->getEntity()->getOneFile();
$dataForTables = ['columns' => [], 'rows' => []];
if (!$model->getIsNewEntity() && $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC) {
    $constants = $model->mergeConstantAndRules;

    if ($model->table_type == ReportFormTemplateEntity::REPORT_TABLE_TYPE_CONST) {
        $dataForTables = [
            'columns' => $constants,
            'rows' => $model->groups
        ];
    } else {
        $dataForTables = [
            'columns' => $model->groups,
            'rows' => $constants
        ];
    }
}

$form = ActiveForm::begin([
    'id' => 'reporttemplate-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'errorSummaryCssClass' => 'border border-danger rounded p-2 mb-3',
    'options' => [
        'enctype' => 'multipart/form-data'
    ],
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{hint}",
        'errorOptions' => [
            'encode' => false,
        ],
    ],
]); ?>
    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-12 col-md-7">
            <?= $form->field($model, 'name'); ?>
        </div>
        <div class="col-12 col-md-5">
            <?= $form->field($model, 'report_id')->widget(Select2::class, [
                'data' => ($model->getIsNewEntity() ? $model->reports : [$model->report_id => $model->reports[$model->report_id]]),
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                    'readonly' => !$model->getIsNewEntity() && !$model->report_id,
                    'disabled' => !$model->getIsNewEntity() && !$model->report_id
                ],
                'pluginOptions' => ['allowClear' => true],
                'pluginEvents' => [
                    'select2:select' => 'function() { 
                        let fieldType = "' . Html::getInputId($model, 'form_type') . '",
                            fieldJob = "' . Html::getInputId($model, 'form_usejobs') . '";
                    
                        $("#" + fieldType + ",#" + fieldJob).attr({"readonly": false, "disabled": false}); 
                    }',
                    'select2:unselect' => 'function() { 
                        let fieldType = "' . Html::getInputId($model, 'form_type') . '",
                            fieldJob = "' . Html::getInputId($model, 'form_usejobs') . '";
                    
                        $("#" + fieldType + ",#" + fieldJob).val("").attr({"readonly": true, "disabled": true}).trigger("change");
                    }',
                ]
            ]); ?>
        </div>
        <div class="col-12 col-md-4">
            <?= $form->field($model, 'form_type')
                ->dropDownList(TemplateHelper::getTypes(), [
                    'prompt' => Yii::t('views', 'Выберите'),
                    'onchange' => 'getData($(this))',
                    'readonly' => !$model->report_id,
                    'disabled' => !$model->report_id
                ]); ?>
        </div>
        <div class="col-12 col-md-4">
            <?= $form->field($model, 'form_usejobs')
                ->dropDownList(CommonHelper::getDefaultDropdown(), [
                    'prompt' => Yii::t('views', 'Выберите'),
                    'readonly' => !$model->report_id,
                    'disabled' => !$model->report_id,
                    'onchange' => 'openCloseFileBlock($(this))'
                ]); ?>
        </div>
        <div class="col-12 col-md-4">
            <?= $form->field($model, 'form_datetime')
                ->dropDownList(TemplateHelper::getDatetimeTypes(), [
                    'prompt' => Yii::t('views', 'Выберите'),
                    'readonly' => !$model->report_id || $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE,
                    'disabled' => !$model->report_id || $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE,
                    'onChange' => 'changeFieldsWhenDatetimeSelect($(this))'
            ]); ?>
        </div>
    </div>
    <div class="row <?= $model->getIsNewEntity() || !$model->form_usejobs ? 'd-none' : ''; ?>" id="saveFileData">
        <div class="col-12 col-md-4 col-xl-3">
            <?= $form->field($model, 'limit_maxfiles')
                ->textInput(['type' => 'number'])
                ->hint(Yii::t('models', 'При достижении данного количества, файлы, сформированные по этому шаблону начнут удаляться с наиболее старого'), ['class' => "form-text text-justify"]);
            ?>
        </div>
        <div class="col-12 col-md-4 col-xl-3">
            <?= $form->field($model, 'limit_maxsavetime')
                ->textInput(['type' => 'number'])
                ->hint(Yii::t('models', 'По прошествии данного времени, файлы, сформированные по этому шаблону начнут удаляться вне зависимости от количества'), ['class' => "form-text text-justify"]);
            ?>
        </div>
    </div>

    <div class="row <?= (!$model->report_id || $model->form_type != ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC) ? 'd-none' : ''; ?>" id="dynamicTemplate">
        <hr />
        <div class="col-12 col-md-4 col-xl-3">
            <?= $form->field($model, 'table_type')
                ->dropDownList(TemplateHelper::getTableTypes(), [
                    'prompt' => Yii::t('views', 'Выберите'),
                    'readonly' => !$model->getIsNewEntity(),
                    'disabled' => !$model->getIsNewEntity(),
                    'onchange' => 'pullData($(this))'
                ]); ?>
        </div>
        <div class="col-12 col-md-4 col-xl-3">
            <?= $form->field($model, 'use_appg')
                ->dropDownList(CommonHelper::getDefaultDropdown(), [
                    'prompt' => Yii::t('views', 'Выберите'),
                ]); ?>
        </div>
        <div class="col-12 col-md-4 col-xl-3">
            <?= $form->field($model, 'use_grouptype')
                ->dropDownList(CommonHelper::getDefaultDropdown(), [
                    'prompt' => Yii::t('views', 'Выберите'),
                ]); ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'table_columns')->widget(Select2::class, [
                'data' => $dataForTables['columns'],
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true,
                ],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'table_rows')->widget(Select2::class, [
                'data' => $dataForTables['rows'],
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true,
                ],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>
    </div>
    <div class="row mb-3 <?= (!$model->report_id || $model->form_type != ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE) ? 'd-none' : ''; ?>" id="staticTemplate">
        <hr />
        <?= AttachFileWidget::widget([
            'model' => $model->getEntity(),
            'workMode' => AttachFileWidget::MODE_ONE,
            'isNewRecord' => $model->getIsNewEntity(),
            'canDeleted' => $canDeleted,
            'uploadButtonTitle' => 'Прикрепить шаблон отчета',
            'uploadButtonOptions' => 'w-100 mb-2',
            'uploadButtonHintText' => Yii::t('views', "Вы можете загрузить шаблон файла для " .
                "формирования таблицы в том виде, в котором Вам необходимо. Следует учитывать, что в данный шаблон, подставятся " .
                "исключительно данные констант и математических правил. Для того, чтобы в нужное место, подставилась нужные данные, " .
                "в соответствующей ячейке шаблона указать название записи в БД для константы или математического правила и, в " .
                "двойных фигурных скобках. Например, <strong>{{nar_5_43}}</strong> или <strong>{{vseg_ao}}</strong>. Кроме того, Вы " .
                "можете использовать указатель периода расчета: M, D, Y через разделительный символ #. При этом, период расчета для " .
                "указателей дат, будет ограничен выбранным периодом для всего отчета<br />Если Вы, хотите указать " .
                "в шаблоне период дат, за который производится расчет, укажите в <strong>ОТДЕЛЬНОЙ</strong> ячейке идентификатор " .
                "<strong>#period#</strong><br /><br /><span class='text-dark'>ПРИМЕР. При расчете за период с 01.10.2021 по 20.02.2022 " .
                "года, константы и математические правила будут заменены на: <ul class='mb-0'><li><strong>{{nar_5_43}}</strong> сумма " .
                "данных за весь период без ограничений.</li><li><strong>{{nar_5_43#D}}</strong> сумма данных за КРАЙНИЕ СУТКИ периода " .
                "расчета 20.02.2022</li><li><strong>{{nar_5_43#M}}</strong> сумма данных за МЕСЯЦ крайнего числа периода расчета - за фераль " .
                "2022 года</li><li><strong>{{nar_5_43#Y}}</strong> сумма данных за ГОД крайнего числа периода расчета - за 2022 год</li></ul>" .
                "При этом, следует учитывать, что, если, у математического правила указано ограничение на конктерные группы, то данный фильтр " .
                "также применится</span>")
        ]); ?>
    </div>

    <div class="row mt-4">
        <div class="col-12 mb-2 d-grid">
            <?php if (!$view) {
                echo Html::submitButton(Yii::t('views', $model->getIsNewEntity() ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']);
            } ?>
        </div>
    </div>
<?php

ActiveForm::end();

$urlForSelectData = Json::htmlEncode(Url::to(['getselectdata']));
$fieldIds = Json::encode([
    'reportInput' => Html::getInputId($model, 'report_id'),
    'reportDatetimeInput' => Html::getInputId($model, 'form_datetime'),
    'reportUseJobs' => Html::getInputId($model, 'form_usejobs'),
    'reportUseAppg' => Html::getInputId($model, 'use_appg'),
    'reportUseGroupType' => Html::getInputId($model, 'use_grouptype'),
    'tableRows' => Html::getInputId($model, 'table_rows'),
    'tableColumns' => Html::getInputId($model, 'table_columns'),
    'tableType' => Html::getInputId($model, 'table_type'),
    'saveMaxFiles' => Html::getInputId($model, 'limit_maxfiles'),
    'saveMaxTime' => Html::getInputId($model, 'limit_maxsavetime'),
]);

$valuesFromEntity = Json::encode([
    'needGetData' => ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC,
    'tableDatetimePeriod' => ReportFormTemplateEntity::REPORT_DATETIME_PERIOD,
    'tableTypeConstant' => ReportFormTemplateEntity::REPORT_TABLE_TYPE_CONST,
]);

$this->registerJs(<<< JS
    var dataForPulling;

    var fieldIds = $fieldIds,
        valuesFromEntity = $valuesFromEntity;

    function getData(element)
    {
        if ( element.val() ) {
            if ( element.val() == valuesFromEntity["needGetData"] ) {
                $.ajax({
                    url: $urlForSelectData,
                    data: {"report_id": $("#" + fieldIds["reportInput"]).val()},
                    beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
                    complete: function(xhr, textStatus) {
                        $("#hidescreen, #loadingData").fadeOut(10);
                        if ( xhr.status == 403 ) {
                            generateToast('error', langMessages.forbiddenTemplate);
                        }
                    },
                    success: function (data) {
                        dataForPulling = data;
                        $("#dynamicTemplate").removeClass("d-none");
                        $("#staticTemplate").addClass("d-none");

                        $("#" + fieldIds["reportResultInput"] + ",#" + fieldIds["reportDatetimeInput"]).attr({"readonly": false, "disabled": false});
                        $("#" + fieldIds["reportDatetimeInput"]).val(valuesFromEntity["tableDatetime"]);
                    }
                });    
            } else {
                $("#staticTemplate").removeClass("d-none");
                $("#dynamicTemplate").addClass("d-none").find("input, select").val("").trigger("change");
                
                $("#" + fieldIds["reportDatetimeInput"]).val(valuesFromEntity["tableDatetimePeriod"]).attr({"readonly": true, "disabled": true}).trigger("change");
            }
        } else {
            $("#" + fieldIds["reportResultInput"] + ",#" + fieldIds["reportDatetimeInput"]).val("").attr({"readonly": true, "disabled": true}).trigger("change");
            $("#dynamicTemplate, #staticTemplate").addClass("d-none").find("input, select").val("").trigger("change");
        }
    }
    
    function openCloseFileBlock(element)
    {
        let selectValue = element.val();
        if ( selectValue && selectValue == 1) {
            $("#saveFileData").removeClass("d-none");
            return;
        } 
        
        $("#saveFileData").addClass("d-none");
    }
    
    function pullData(element)
    {
        let selectValue = element.val(),
            blockUseGroupTypeState = ( $("#" + fieldIds['tableType']).val() != valuesFromEntity['tableTypeConstant'] ) ? true : false;
        
        
        $("#" + fieldIds["tableRows"] + ", #" + fieldIds["tableColumns"]).val("").find("option").each(function(){ $(this).remove(); });
        $('#' + fieldIds["reportUseGroupType"]).val("").attr({"disabled": blockUseGroupTypeState, "readonly": blockUseGroupTypeState});    

        if ( selectValue.length == 0 ) {
            return false;
        }
        
        let appendColumns = dataForPulling.groups,
            appendRows = dataForPulling.mergeConstantAndRules;
        
        if ( selectValue == valuesFromEntity["tableTypeConstant"] ) {
            appendColumns = dataForPulling.mergeConstantAndRules;
            appendRows = dataForPulling.groups;
        }     
            
        $.each(appendColumns, function(index, value) {
            let appendOption = new Option(value, index, false, false);
            $("#" + fieldIds["tableColumns"]).append(appendOption);                
        });    
            
        $.each(appendRows, function(index, value) {
            let appendOption = new Option(value, index, false, false);
            $("#" + fieldIds["tableRows"]).append(appendOption);                
        }); 
        
        $("#" + fieldIds["tableRows"] + ", #" + fieldIds["tableColumns"]).trigger("change");
    }
    
    function changeFieldsWhenDatetimeSelect(element)
    {
        let selectValue = element.val();
        
        if ( selectValue ) {
            if ( selectValue != valuesFromEntity['tableDatetimePeriod'] ) {
                $("#" + fieldIds['tableType']).val(valuesFromEntity['tableTypeConstant']);
                $("#" + fieldIds['tableGroupType']).val(valuesFromEntity['tableGroupType']);
                $("#" + fieldIds['reportUseAppg']).val(0);
                $("#" + fieldIds['tableType'] + ", #" + fieldIds['tableGroupType'] + ", #" + fieldIds['reportUseAppg']).attr({'readonly': true, 'disabled': true}).trigger('change');
                $("#" + fieldIds['tableColumns']).attr({'multiple': false});
            } else {
                $("#" + fieldIds['tableColumns']).attr({'multiple': 'multiple'});
                $("#" + fieldIds['tableType'] + ", #" + fieldIds['tableGroupType'] + ", #" + fieldIds['reportUseAppg']).attr({'readonly': false, 'disabled': false}).trigger('change');        
            }    
        } else {
            $("#" + fieldIds['tableColumns']).attr({'multiple': 'multiple'});
            $("#" + fieldIds['tableType'] + ", #" + fieldIds['tableGroupType'] + ", #" + fieldIds['reportUseAppg']).attr({'readonly': false, 'disabled': false}).trigger('change');
        }
    }
JS, $this::POS_BEGIN);
