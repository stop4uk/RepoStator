<?php

/**
 * @var \yii\web\View $this
 * @var int $k
 * @var array $model
 * @var string $template
 * @var mixed $additionalInformation
 * @var string $widgetID
 * @var string $buttonDeletePlaceBlock
 * @var string $buttonDeleteName
 * @var string|null $buttonDeleteClasses
 */

if ( isset($contentPath) ) {
    $content = $this->render($contentPath, [
        'k' => $k,
        'model' => $model,
        'template' => $template,
        'widgetID' => $widgetID,
        'additionalInformation' => $additionalInformation,
        'buttonDeletePlaceBlock' => $buttonDeletePlaceBlock,
        'buttonDeleteName' => $buttonDeleteName,
        'buttonDeleteClasses' => $buttonDeleteClasses
    ]);
}

echo $content;
