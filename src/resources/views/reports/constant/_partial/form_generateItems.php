<?php

use yii\bootstrap5\Html;
use kartik\select2\Select2;

/**
 * @var int $k
 * @var int|string $widgetID
 * @var mixed $additionalInformation
 * @var \app\modules\reports\models\ConstantModel $model
 * @var string $buttonDeletePlaceBlock
 */

?>

<tr class="repeater-item_<?= $widgetID ?>" data-id="<?= $k ?>">
    <td id="buttonDeleteBlock_<?= $widgetID ?>" class="<?= $buttonDeletePlaceBlock . '_' . $widgetID ?>">
        <?php
            echo Html::button('<i class="bi bi-copy" id="copyButton_' . $widgetID . '_' . $k . '"></i>',['class' => 'copy btn btn-info p-1']);
            echo Html::button('<i class="bi bi-dash"  id="dashButton_' . $widgetID . '_' . $k . '"></i>',['class' => 'remove btn btn-danger p-1 ms-1']);
        ?>
    </td>
    <td class="text-center">
        <?= $k+1 ?>
    </td>
    <td>
        <?= Html::activeTextInput($model, "[$k]record", ['class' => 'form-control']); ?>
    </td>
    <td>
        <?= Html::activeTextInput($model, "[$k]name", ['class' => 'form-control']); ?>
    </td>
    <td>
        <?= Html::activeTextInput($model, "[$k]name_full", ['class' => 'form-control']); ?>
    </td>
    <td>
        <?= Html::activeTextInput($model, "[$k]description", ['class' => 'form-control noncopyable']); ?>
    </td>
    <td>
        <?= Html::activeTextInput($model, "[$k]union_rules", ['class' => 'form-control']); ?>
    </td>
    <td>
        <?= Select2::widget([
            'model' => $model,
            'attribute' => "[$k]reports_only",
            'data' => $model->reports,
            'options' => [
                'placeholder' => '',
                'multiple' => true,
            ],
            'pluginOptions' => ['allowClear' => true],
        ]); ?>
    </td>
</tr>
