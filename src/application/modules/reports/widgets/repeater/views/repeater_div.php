<?php

use yii\bootstrap5\Html;

/**
 * @var \yii\web\View $this
 * @var int $k
 * @var array $model
 * @var mixed $additionalInformation
 * @var string $buttonDeletePlaceBlock
 * @var string $buttonDeleteName
 * @var string|null $buttonDeleteClasses
 * @var bool $buttonDeleteShowLabelBefore
 */

?>

<div class="row repeater-item_<?= $widgetID ?? null ?> mb-1" data-id="<?= $k ?? 0 ?>">
    <?php
        if (isset($contentPath)) {
            $content = $this->render($contentPath, [
                'additionalInformation' => $additionalInformation,
                'widgetID' => $widgetID,
                'model' => $model,
                'k' => $k,
            ]);
        }

        echo $content;
    ?>
    <div class="<?= $buttonDeletePlaceBlock ?>"  id="buttonDeleteBlock_<?= $widgetID ?>">
        <?php
            if ($buttonDeleteShowLabelBefore) {
                echo Html::label('&nbsp;', '', ['class' => 'form-label']);
            }

            echo Html::a($buttonDeleteName, '#', ['class' => 'remove ' . $buttonDeleteClasses]);
        ?>
    </div>
</div>