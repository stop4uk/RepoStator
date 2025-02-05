<?php

namespace app\modules\reports\components\formReport\workers;

use app\modules\reports\components\formReport\base\BaseWorker;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\fomrReport\workers
 */
final class FormDirectWorker extends BaseWorker
{
    public function run()
    {
        return $this->processor->form();
    }
}
