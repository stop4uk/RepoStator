<?php

/**
 * @var \yii\web\View $this
 * @var \app\models\report\StructureModel $model
 */

use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;

use app\helpers\CommonHelper;
use app\widgets\repeater\Repeater;

$form = ActiveForm::begin([
    'id' => 'reportstructure-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'errorSummaryCssClass' => 'border border-danger rounded p-2 mb-3',
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{hint}"
    ]
]); ?>
    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-12 col-md-7">
            <?= $form->field($model, 'name'); ?>
        </div>
        <div class="col-12 col-md-5">
            <?= $form->field($model, 'report_id')->widget(Select2::class, [
                'data' => $model->reports,
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                    'readonly' => !$model->getIsNewEntity(),
                    'disabled' => !$model->getIsNewEntity()
                ],
                'pluginEvents' => [
                    'select2:select' => 'function(e) { loadData(e); }',
                    'select2:unselect' => 'function(e) { removeData(); }'
                ],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'use_union_rules')
                ->dropDownList(CommonHelper::getDefaultDropdown())
                ->hint(Yii::t('models', 'Если, выбрана группировка, то при формировании структуры для заполнения, ' .
                    'будут применены правила, указанные для каждой константы в поле "Правила объединения" и, они будут соединены в ' .
                    'своеобразный раздел в виде визуального элемента "Аккордеон"'), ['class' => 'form-text text-justify']); ?>
        </div>
        <div class="col-12 <?= $model->getIsNewEntity() ? 'd-none' : ''; ?>" id="groupOnlyBlock">
            <?= $form->field($model, "groups_only")->widget(Select2::class, [
                'data' => $model->groupsCanSent,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true,
                ],
                'pluginOptions' => ['allowClear' => true],
            ])->hint(Yii::t('models', 'Данное поле будет иметь приоритет над такой же структурой и незаполненным полем. ' .
                'Поэтому, когда пользователь начнет заполнять указанный отчет за одно из подразделений перечисленных в этом поле, откроется ' .
                ' именно эта структура, а не общая. Учтите, что для одного подразделения не может быть двух разных структур передачи, ' .
                ' предназначенных для одного отчета'), ['class' => 'form-text text-justify']); ?>
        </div>
    </div>

    <div class="row <?= $model->getIsNewEntity() ? 'd-none' : ''; ?>" id="repeaterStructureBlock">
        <label class="text-center mt-1 h3"><?= Yii::t('views', 'Структуры передачи'); ?></label>
        <?= $form->field($model, 'contentGroups[]')->hiddenInput()->label(false); ?>
        <?= $form->field($model, 'contentConstants[]')->hiddenInput()->label(false); ?>

        <?= Repeater::widget([
            'appendAction' => Url::to(['addStructure']),
            'removeAction' => Url::to(['deleteStructure']),
            'form' => $form,
            'models' => $model->getFieldsForStructures(),
            'modelView' => '@resources/views/reports/structure/_partial/form_generateItems',
            'buttonName' => Yii::t('views', 'Добавить часть структуры'),
            'buttonClasses' => 'btn btn-dark btn-sm',
            'buttonPlaceBlock' => 'col-12',
            'buttonDeleteName' => '<i class="bi bi-trash"></i>',
            'buttonDeleteClasses' => 'btn btn-danger w-100',
            'buttonDeletePlaceBlock' => 'col-md-1 text-center',
            'additionalField' => $model->report_id
        ]); ?>
        <hr class="mb-4"/>
    </div>

    <div class="row mt-4">
        <div class="col-12 mb-2 d-grid">
            <?= Html::submitButton(Yii::t('views', $model->getIsNewEntity() ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
<?php

ActiveForm::end();

$urlForSelectData = Json::htmlEncode(Url::to(['getselectdata']));
$this->registerJs(<<< JS
    function loadData(e) 
    {
        let selectItem = e.params.data.id;    
    
        $.ajax({
            url: $urlForSelectData,
            data: {'report_id': selectItem},
            beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
            complete: function(xhr, textStatus) {
                $("#hidescreen, #loadingData").fadeOut(10);
                if ( xhr.status == 403 ) {
                    generateToast('error', langMessages.forbiddenTemplate);
                }
            },
            success: function (data) {
                $("[id^='structuremodel-contentconstants'], #structuremodel-groups_only").find('option').each(function() {
                    $(this).remove();
                });
                
                $.each(data.groups, function(index, value){
                    let appendOption = new Option(value, index, false, false);
                    $("#structuremodel-groups_only").append(appendOption);
                });
                
                $("#repeaterStructureBlock, #groupOnlyBlock").removeClass("d-none");
                $("#additionalField").val(selectItem);
                
                $("[id^='structuremodel-contentconstants']").each(function() {
                    let select2Id = $(this).attr('id');
                    
                    $.each(data.contentConstants, function(index, value){
                        let appendOption = new Option(value, index, false, false);
                        $("#" + select2Id).append(appendOption);
                    });
                    
                    $(this).trigger('change');
                });
            },
       });
    }    
    
    function removeData() 
    {
        $("#repeaterStructureBlock, #groupOnlyBlock").addClass("d-none");
        $("#additionalField").val("");
        
        $("[id^='structuremodel-contentconstants']").find('option').each(function() {
            $(this).remove();
        });
    }
JS);
