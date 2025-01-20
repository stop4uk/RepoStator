<?php

use yii\helpers\{
    Json,
    Url
};
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;

/**
 * @var \yii\web\View $this
 * @var \app\modules\reports\models\ConstantRuleModel $model
 */

$form = ActiveForm::begin([
    'id' => 'reportconstantrule-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>

    <div class="row">
        <div class="col-12 col-md-4">
            <?= $form->field($model, 'record')->hint(Yii::t('models', 'Должно быть уникальным для констант и правил сложения')); ?>
        </div>
        <div class="col-12 col-md-8">
            <?= $form->field($model, 'name'); ?>
        </div>

    </div>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'report_id')->widget(Select2::class, [
                'data' => $model->reports,
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                ],
                'pluginEvents' => [
                    'select2:select select2:unselect' => 'function() { getData(); }',
                ],
                'pluginOptions' => ['allowClear' => true],
            ])->hint(Yii::t('models', 'Отчет, к которому может быть применено данное правило. Условие влияет на ' .
                'выбор констант и правил при создании или изменении шаблона формирования. Если не указано, правило будет применено ' .
                'ко всем отчетам в системе'), ['class' => 'form-text text-justify']); ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'groups_only')->widget(Select2::class, [
                'data' => $model->groups,
                'options' => [
                    'placeholder' => '',
                    'multiple' => true,
                ],
                'pluginOptions' => ['allowClear' => true],
            ])->hint(Yii::t('models', 'Ограниченный список групп, сведения по которым, будут учитываться. Например, если, ' .
                'указать в поле "Группа 1" и "Группа 2", то данное правило возьмет все сведения из отчета и выберет среди них только те, которые, ' .
                'были переданны от имени этих групп'), ['class' => 'form-text text-justify']); ?>
        </div>
        <div class="col-7">
            <?= $form->field($model, 'rule', ['errorOptions' => ['encode' => false]])->textarea(); ?>
        </div>
        <div class="col-5">
            <?= Html::label(Yii::t('models', 'Быстрый поиск константы'), '', ['class' => 'form-label']); ?>
            <?= Select2::widget([
                'name' => 'constantHelper',
                'id' => 'constantHelper',
                'data' => $model->constants,
                'options' => [
                    'placeholder' => '',
                    'multiple' => false,
                ],
                'pluginEvents' => [
                    'select2:select' => 'function(e) { pasteItem(e); }',
                ],
                'pluginOptions' => ['allowClear' => true],
            ]); ?>
        </div>
        <div class="col-12 text-justify lh-1">
            <span class="form-text text-muted">
                <?= Yii::t('models', 'Простое математическое выражение. Внутри, вместо цифр, ' .
                    'используюся идентификаторы констант (поле "Идентификатор"), которые заносятся в БД через структуры передачи. ' .
                    'Например, константа для заполнения сведений по ст. 5.43 КоАП, имеет идентификатор "nar5_43" ' .
                    '<span class="text-danger fw-bold">ВСЕ идентификаторы, ОБЯЗАНЫ быть взяты в двойные кавычки</span>. ' .
                    'Самое простое математическое выражение, которое возможно использовать, будет выглядеть так: ' .
                    '<strong>"nar5_43"+"nar11_21_1"</strong> (все пробелы будут исключены). Разрешается формировать и боле сложные ' .
                    'правила. Например, <strong>(("nar5_43"+"nar11_21_1")*0.15)/2-(1+"main_field")') ?>
            </span>
        </div>
    </div>
    <hr />
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
            <?= Html::submitButton(Yii::t('views', $model->getIsNewEntity() ? 'Добавить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

<?php

ActiveForm::end();

$targetInput = Html::getInputId($model, 'rule');
$reportIDFiled = Html::getInputId($model, 'report_id');
$urlForSelectData = Json::htmlEncode(Url::to(['getselectdata']));
$this->registerJs(<<< JS
    function pasteItem(e)
    {
        let selectId = e.params.data.id,
            nowRule = $("#$targetInput").val();
        
        $("#$targetInput").val(nowRule + '"' + selectId + '"');
    }
    
    function getData(e) 
    {
        let selectItem = $("#$reportIDFiled").val()
            urlForGetData = selectItem ? $urlForSelectData+"?report_id="+selectItem : $urlForSelectData;    
    
        $.ajax({
            url: urlForGetData,
            beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
            complete: function(xhr, textStatus) {
                $("#hidescreen, #loadingData").fadeOut(10);
                if ( xhr.status == 403 ) {
                    generateToast('error', langMessages.forbiddenTemplate);
                }
            },
            success: function (data) {
                $("#constantHelper").find('option').each(function() {
                    $(this).remove();
                });
                
                $("#constantHelper").append(new Option("", "", false, false));
                $.each(data.contentConstants, function(index, value){
                    let appendOption = new Option(value, index, false, false);
                    $("#constantHelper").append(appendOption);
                });
                
                $("#constantrulemodel-groups_only").append(new Option("", "", false, false));
                $.each(data.groups, function(index, value){
                    let appendOption = new Option(value, index, false, false);
                    $("#constantrulemodel-groups_only").append(appendOption);
                });

                $("#constantHelper, #constantrulemodel-groups_only").trigger('change');
            },
       });
    }    
JS);