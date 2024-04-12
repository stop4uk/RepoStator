<?php

namespace app\factories;

use app\processors\{
    ToFileProcessor,
    ToFileByJobProcessor
};
use app\interfaces\ProcessorInterface;
use app\repositories\report\TemplateRepository;
use app\forms\StatisticForm;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\factories
 */
class FormTemplateFactory
{
    public static function process(StatisticForm $form): ProcessorInterface
    {
        $template = TemplateRepository::get($form->template);

        return match( (bool)$template->form_usejobs ) {
            true => new ToFileByJobProcessor($form, $template),
            false => new ToFileProcessor($form, $template)
        };
    }
}