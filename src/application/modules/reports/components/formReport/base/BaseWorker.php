<?php

namespace app\modules\reports\components\formReport\base;

use app\modules\reports\components\formReport\dto\StatFormDTO;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\fomrReport\base
 */
abstract class BaseWorker
{
    protected BaseProcessor $processor;
    protected StatFormDTO $form;

    public function __construct(
        BaseProcessor $processor,
        StatFormDTO $form
    )
    {
        $this->processor = $processor;
        $this->form = $form;
    }

    abstract public function run();
}