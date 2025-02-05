<?php

namespace app\modules\reports\components\formReport\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;

use app\modules\reports\components\formReport\base\BaseProcessor;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\formReport\jobs
 */
final class FormTemplateJob extends BaseObject implements JobInterface
{
    public BaseProcessor $processor;
    public string $jobID;

    public function execute($queue): void
    {
        $this->processor->setJobID($this->jobID);
        $this->processor->form();
    }
}