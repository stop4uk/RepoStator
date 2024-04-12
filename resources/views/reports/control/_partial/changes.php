<?php

/**
 * @var \yii\web\View $this
 * @var \app\entities\report\ReportDataChangeEntity[] $changes
 */

use yii\bootstrap5\Accordion;
use app\helpers\report\DataChangeHelper;

echo Accordion::widget([
    'items' => DataChangeHelper::getItemForAccordion($changes),
    'itemToggleOptions' => [
        'class' => 'py-1 text-start'
    ],
    'options' => ['tag' => 'div'],
]);

$this->registerCss(<<< CSS
    .accordion-body {
        padding-left: 0!important;
    }
CSS);
