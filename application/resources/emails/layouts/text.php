<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

$this->beginPage();
    $this->beginBody();
        echo $content;
    $this->endBody();
$this->endPage();
