<?php

/**
 * @var \yii\web\View $this
 * @var \app\models\report\StructureModel $model
 * @var int $k
 * @var int|string|null $additionalField
 * @var string $buttonDeleteClasses
 * @var string $buttonDeleteName
 * @var string $buttonDeletePlaceBlock
 */

use yii\bootstrap5\Html;

?>

<div class="row repeater-item mb-1" data-id="<?= $k ?>">
    <?php
    if (isset($contentPath)) {
        $content = $this->render($contentPath, ['model' => $model, 'k' => $k, 'additionalField' => $additionalField]);
    }

    echo $content;
    ?>
    <div class="<?= $buttonDeletePlaceBlock; ?>" id="buttonDeleteBlock">
        <?php
        echo Html::label('&nbsp;', '', ['class' => 'form-label d-block']);
        echo Html::a($buttonDeleteName, 'javascript:;', ['class' => 'remove ' . $buttonDeleteClasses]);
        ?>
    </div>
</div>