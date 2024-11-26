<?php

namespace app\interfaces;

use app\entities\report\ReportFormTemplateEntity;
use app\forms\StatisticForm;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\interfaces
 */
interface ProcessorInterface
{
    public function __construct(
        StatisticForm $form,
        ReportFormTemplateEntity $template
    );

    public function run();
}
