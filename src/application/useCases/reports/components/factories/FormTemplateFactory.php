<?php

namespace app\useCases\reports\components\factories;

use app\useCases\reports\{
    components\base\BaseProcessorInterface,
    components\processors\ToFileProcessor,
    components\processors\ToFileByJobProcessor,
    repositories\TemplateRepository,
    forms\StatisticForm
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\factories
 */
class FormTemplateFactory
{
    public static function process(StatisticForm $form): BaseProcessorInterface
    {
        $template = TemplateRepository::get($form->template);

        return match( (bool)$template->form_usejobs ) {
            true => new ToFileByJobProcessor($form, $template),
            false => new ToFileProcessor($form, $template)
        };
    }
}