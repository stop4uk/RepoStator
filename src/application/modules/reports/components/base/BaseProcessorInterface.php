<?php

namespace app\useCases\reports\components\base;

use app\useCases\reports\entities\ReportFormTemplateEntity;
use app\useCases\reports\forms\StatisticForm;

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
