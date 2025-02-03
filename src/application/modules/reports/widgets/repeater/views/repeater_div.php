<?php

use yii\bootstrap5\Html;

/**
 * @var \yii\web\View $this
 * @var int $k
 * @var array $model
 * @var mixed $additionalInformation
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
    <div class="col-1" id="buttonDeleteBlock_<?= $widgetID ?? null ?>">
        <?= Html::button('<i class="bi bi-trash"></i>', ['class' => 'remove btn btn-danger']);?>
    </div>
</div>