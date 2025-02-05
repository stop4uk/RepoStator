<?php

namespace app\modules\reports\components\formReport;

use Yii;
use yii\web\Response;
use app\modules\reports\{
    components\formReport\base\BaseWorker,
    components\formReport\processors\FromDynamic,
    components\formReport\processors\FromTemplate,
    components\formReport\workers\FormDirectWorker,
    components\formReport\workers\FormJobWorker,
    entities\ReportFormTemplateEntity,
    repositories\TemplateRepository,
    forms\StatisticForm
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\formReport
 */
final class FormFactory
{
    private string $templateID;
    private StatisticForm $form;
    private ReportFormTemplateEntity $template;

    public function __construct(StatisticForm $form)
    {
        $this->form = $form;

        $this->templateID = $form->template;
        $this->template = TemplateRepository::get($this->templateID);
    }

    public function process(): BaseWorker|Response
    {
        $processor = Yii::createObject(match($this->template->form_type) {
            $this->template::REPORT_TYPE_DYNAMIC => FromDynamic::class,
            $this->template::REPORT_TYPE_TEMPLATE => FromTemplate::class
        }, [$this->form, $this->template]);

        $worker = Yii::createObject(match((bool)$this->template->form_usejobs) {
            true => FormJobWorker::class,
            false => FormDirectWorker::class
        }, [$processor, $this->form]);

        return $worker->run();
    }
}