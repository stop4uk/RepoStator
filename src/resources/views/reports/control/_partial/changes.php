<?php

use yii\bootstrap5\Accordion;
use app\useCases\reports\helpers\DataChangeHelper;

/**
 * @var \yii\web\View $this
 * @var \app\useCases\reports\entities\ReportDataChangeEntity[] $changes
 */

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
