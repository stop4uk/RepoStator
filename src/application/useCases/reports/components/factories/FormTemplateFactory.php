<?php

namespace app\useCases\reports\components\factories;

use app\useCases\reports\{
    components\base\BaseProcessorInterface,
    components\processors\ToFileBaseProcessor,
    components\processors\ToFileByJobBaseProcessor,
    repositories\TemplateBaseRepository,
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
        $template = TemplateBaseRepository::get($form->template);

        return match( (bool)$template->form_usejobs ) {
            true => new ToFileByJobBaseProcessor($form, $template),
            false => new ToFileBaseProcessor($form, $template)
        };
    }
}