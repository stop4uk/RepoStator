<?php

/**
 * @var \yii\web\View $this
 * @var int $k
 * @var array $model
 * @var string $template
 * @var mixed $additionalInformation
 * @var string $widgetID
 */

if ( isset($contentPath) ) {
    $content = $this->render($contentPath, [
        'additionalInformation' => $additionalInformation,
        'widgetID' => $widgetID,
        'template' => $template,
        'model' => $model,
        'k' => $k,
    ]);
}

echo $content;
