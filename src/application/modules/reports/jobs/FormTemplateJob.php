<?php

namespace jobs;

use components\processors\ToFileBaseProcessor;
use entities\ReportFormTemplateEntity;
use forms\StatisticForm;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\jobs
 */
final class FormTemplateJob extends BaseObject implements JobInterface
{
    public StatisticForm $form;
    public ReportFormTemplateEntity $template;
    public string $jobID;

    public function execute($queue)
    {
        $processor = new ToFileBaseProcessor($this->form, $this->template);
        $processor->setJobID($this->jobID);
        $processor->run();
    }
}