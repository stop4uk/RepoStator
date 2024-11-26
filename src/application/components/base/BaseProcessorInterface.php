<?php

namespace app\components\base;

use entities\ReportFormTemplateEntity;
use forms\StatisticForm;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\interfaces
 */
interface BaseProcessorInterface
{
    public function __construct(
        StatisticForm $form,
        ReportFormTemplateEntity $template
    );

    public function run();
}
