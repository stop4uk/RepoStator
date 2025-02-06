<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    ActiveForm,
    Html
};

use app\widgets\{
    repeater\Repeater,
    duplicating\DuplicatingWidget
};

/**
 * @var \app\modules\reports\models\ConstantModel $model
 * @var \app\modules\reports\models\ConstantModel[] $models
 */

$this->title = Yii::t('views', 'Массовое добавление');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Константы'), 'url' => Url::to(['/reports/constant'])],
];

$tableID = 'table-constant';
$form = ActiveForm::begin([
    'id' => 'masscreateconstant-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
]);

echo DuplicatingWidget::widget([
    'blockID' => 'workBlockForDuplicate_' . $tableID,
    'columnForButton' => 0,
    'buttonClass' => 'btn btn-dark p-1',
    'filterDuplicateElements' => 'noncopyable'
]);

foreach ($models as $i => $model) {
    echo($form->errorSummary($model, [
        'class' => 'alert alert-danger',
        'header' => 'Исправьте ошибки в строке ' . $i + 1 . ':',
    ]));
}

?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive ab-repeater_<?= $tableID ?>">
            <table class="table table-sm list-area" id="<?= $tableID ?>">
                <thead>
                    <tr>
                        <th class="border-top-0 text-center" style="min-width: 6rem;"></th>
                        <th class="border-top-0 text-center" style="min-width: 2rem;">#</th>
                        <th class="border-top-0 text-center" style="min-width: 10rem;"><?= $model->getAttributeLabel('record'); ?></th>
                        <th class="border-top-0 text-center" style="min-width: 10rem;"><?= $model->getAttributeLabel('name'); ?></th>
                        <th class="border-top-0 text-center" style="min-width: 12rem;"><?= $model->getAttributeLabel('name_full'); ?></th>
                        <th class="border-top-0 text-center" style="min-width: 14rem;"><?= $model->getAttributeLabel('description'); ?></th>
                        <th class="border-top-0 text-center" style="min-width: 12rem;"><?= $model->getAttributeLabel('union_rules'); ?></th>
                        <th class="border-top-0 text-center" style="min-width: 14rem;"><?= $model->getAttributeLabel('reports_only'); ?></th>
                    </tr>
                </thead>
                    <tbody id="workBlockForDuplicate_<?= $tableID ?>">
                        <?= Repeater::widget([
                            'widgetID' => $tableID,
                            'template' => Repeater::TEMPLATE_TABLE,
                            'appendAction' => Url::to(['addconstant']),
                            'removeAction' => Url::to(['deleteconstant']),
                            'form' => $form,
                            'models' => $models,
                            'modelView' => '@resources/views/reports/constant/_partial/form_generateItems',
                            'additionalInformation' => []
                        ]); ?>
                    </tbody>
            </table>
            <div class='ab-control d-flex justify-content-center mt-1 mb-1'>
                <button type='button' id='new_repeater_<?= $tableID ?>' class='btn btn-dark new-repeater_<?= $tableID ?>'>
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <?= Html::submitButton('Добавить константы', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php
    ActiveForm::end();