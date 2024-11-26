<?php

namespace app\factories;

use app\components\base\BaseProcessorInterface;
use app\forms\StatisticForm;
use app\processors\{ToFileBaseProcessor, ToFileByJobBaseProcessor};
use app\repositories\report\TemplateBaseRepository;

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