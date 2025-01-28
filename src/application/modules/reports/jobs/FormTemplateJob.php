<?php

namespace app\modules\reports\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;

use app\modules\reports\{
    components\processors\ToFileProcessor,
    entities\ReportFormTemplateEntity,
    forms\StatisticForm
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\jobs
 */
final class FormTemplateJob extends BaseObject implements JobInterface
{
    public StatisticForm $form;
    public ReportFormTemplateEntity $template;
    public string $jobID;

    public function execute($queue)
    {
        $processor = new ToFileProcessor($this->form, $this->template);
        $processor->setJobID($this->jobID);
        $processor->run();
    }
}