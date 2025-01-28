<?php

namespace app\modules\reports\components\base;

use app\modules\reports\{
    entities\ReportFormTemplateEntity,
    forms\StatisticForm
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\base
 */
interface BaseProcessorInterface
{
    public function __construct(
        StatisticForm $form,
        ReportFormTemplateEntity $template
    );

    public function run();
}
