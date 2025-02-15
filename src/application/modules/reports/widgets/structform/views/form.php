<?php

use yii\bootstrap5\{
    ActiveForm,
    Html
};

/**
 * @var \yii\web\View $this
 * @var \app\components\base\BaseModel $model
 * @var array $constants
 * @var string $formId
 * @var string $formField
 * @var bool $view
 */

$firstElement = array_key_first($model->structureContent['groups']);
$form = ActiveForm::begin([
    'id' => $formId,
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
]); ?>
    <div class="row">
        <?php
            echo $form->field($model, $formField . "[]")->hiddenInput()->label(false);
            if (count($model->structureContent['groups']) == 1) {
                echo Html::beginTag('div', ['class' => "col-12"]);
                    echo Html::beginTag('div', ['class' => 'row']);
                        echo $this->render('form_generateItems', [
                            'id' => 0,
                            'elements' => $this->context->formContents($model->structureContent['constants'][0]),
                            'form' => $form,
                            'model' => $model,
                            'formField' => $formField,
                            'constants' => $constants
                        ]);
                    echo Html::endTag('div');
                echo Html::endTag('div');
            } else {
                echo Html::beginTag('div', ['class' => 'col-12 col-md-3 col-xl-3 col-xxl-2', 'id' => 'nav-tab', 'role' => 'tablist']);
                    foreach ($model->structureContent['groups'] as $id => $group) {
                        $showItem = ($firstElement == $id);

                        echo Html::tag('div', Html::button($group, [
                            "class" => "text-start btn btn-outline-primary mb-1 " . ($showItem ? 'active' : ''),
                            "id" => "nav-$id-tab",
                            "type" => "button",
                            "role" => "tab",
                            "data-bs-toggle" => "tab",
                            "data-bs-target" => "#nav-$id",
                            "aria-controls" => "nav-$id",
                            "aria-selected" => (bool)$showItem,
                        ]), ['class' => 'd-grid gap-2']);
                    }
                echo Html::endTag('div');

                echo Html::beginTag('div', ['class' => 'col-12 col-md-8 col-xl-9 tab-content', 'id' => 'tabs-tabContent']);
                    foreach ($model->structureContent['constants'] as $id => $items) {
                        $showItem = ($firstElement == $id);

                        echo Html::beginTag('div', [
                            'class' => 'tab-pane ' . ($showItem ? 'show active' : 'fade'),
                            'id' => "nav-$id",
                            'role' => 'tabpanel',
                            'aria-labelledby' => "nav-$id-tab",
                            'tabindex' => 0
                        ]);
                            echo Html::beginTag('div', ['class' => 'row']);
                                echo $this->render('form_generateItems', [
                                    'id' => $id,
                                    'elements' => $this->context->formContents($items),
                                    'form' => $form,
                                    'model' => $model,
                                    'formField' => $formField,
                                    'constants' => $constants
                                ]);
                            echo Html::endTag('div');
                        echo Html::endTag('div');
                    }
                echo Html::endTag('div');
            }
        ?>
    </div>

    <?php if (!$view ): ?>
        <div class="d-grid gap-2 mt-3">
            <?= Html::submitButton(Yii::t('views', $model->isNewEntity ? 'Отправить' : 'Обновить'), ['class' => 'btn btn-primary']); ?>
        </div>
    <?php endif; ?>

<?php

ActiveForm::end();

if ($view) {
    $this->registerJs(<<<JS
        $("#$formId").find("input, select").attr({"readonly": true, "disabled": true});
JS);
}

$this->registerJs(<<<JS
    if (window.innerWidth > 812) {
        document.getElementsByClassName("js-sidebar-toggle")[0].dispatchEvent(new Event('click'));
    };
JS);
