<?php

use yii\bootstrap5\Html;
use kartik\select2\Select2;

/**
 * @var \yii\web\View $this
 * @var \app\models\report\StructureModel $model
 * @var int|string|null $additionalField
 */

?>
    <div class="col-12 col-md-4">
        <?php
            echo Html::activeLabel($model, "contentGroups[$k]", ['class' => 'form-label']);
            echo Html::activeTextInput($model, "contentGroups[$k]", ['class' => 'form-control']);
        ?>
    </div>
    <div class="col-12 col-md-7">
        <?php
            echo Html::activeLabel($model, "contentConstants[$k]", ['class' => 'form-label']);
            echo Select2::widget([
                'model' => $model,
                'attribute' => "contentConstants[$k]",
                'data' => $model->constants,
                'maintainOrder' => true,
                'showToggleAll' => true,
                'options' => [
                    'multiple' => true,
                    'allowClear' => false,
                    'scrollAfterSelect' => true
                ],
                'pluginEvents' => [
                    'select2:opening' => 'function(e) { open(e, $(this)); }',
                    'select2:select' => 'function(e){ select(e, $(this)); }',
                    'select2:unselect' => 'function(e) { unselect(e, $(this)); }'
                ]
            ]);
            echo Html::tag('span', Yii::t('views', 'Константы будут выведены именно в том порядке, в котором они сюда добавлены. Включая вывод разделов группировки'), ['class' => 'form-text text-justify']);
        ?>
    </div>

<?php

$this->registerJs(<<< JS
    function open(e, element) 
    {
        const select2IdMain = element.attr('id'),
            select2ValuesMain = element.val();
        
        //Обратываем все Repeater select2 с константами и, если, в текущем есть значения, убираем из остальных, 
        //а в этом убираем то, что в остальных
        $("[id^='structuremodel-contentconstants']").not("#" + select2IdMain).each(function() {
            let select2Id = $(this).attr('id'),
                select2Values = $(this).val();
            
            //Убираем из остальных, если, в этом что-то есть
            if ( select2ValuesMain.length ) {
                $.each(select2ValuesMain, function(key, value){
                    $("#" + select2Id).find("option[value='" + value + "']").remove();    
                });    
            }
            
            //Убираем отсюда, если, в остальных что-то есть
            if ( select2Values.length ) {
                $.each(select2Values, function(key, value){
                    $("#" + select2IdMain).find("option[value='" + value + "']").remove();    
                });    
            }
        });
    }
    
    function select(e, element)
    {
        const select2IdMain = element.attr('id'),
            select2Value = e.params.data.id;
        
        //Убираем из остальных выбранную опцию
        $("[id^='structuremodel-contentconstants']").not("#" + select2IdMain).each(function() {
            $(this).find("option[value='" + select2Value + "']").remove();
        });
    }
    
    function unselect(e, element) 
    {
        const select2IdMain = element.attr('id'),
            select2Value = e.params.data.id,
            select2Text = e.params.data.text;
        
        //Добавляем в остальные только что удаленную опцию
        $("[id^='structuremodel-contentconstants']").not("#" + select2IdMain).each(function() {
            if ( $(this).find("option[value='" + select2Value + "']").length === 0 ) {
                let appendOption = new Option(select2Text, select2Value, false, false);
                $(this).append(appendOption).trigger('change');
            }        
        });
    }
JS);