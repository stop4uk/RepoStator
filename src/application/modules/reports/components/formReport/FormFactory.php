<?php

namespace app\modules\reports\components\formReport;

use Yii;
use yii\web\Response;

use app\helpers\CommonHelper;
use app\modules\reports\{
    components\formReport\base\BaseWorker,
    components\formReport\dto\StatFormDTO,
    components\formReport\dto\TemplateDTO,
    components\formReport\processors\FromDynamic,
    components\formReport\processors\FromTemplate,
    components\formReport\workers\FormDirectWorker,
    components\formReport\workers\FormJobWorker,
    entities\ReportFormTemplateEntity,
    forms\StatisticForm,
    repositories\TemplateRepository
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\formReport
 */
final class FormFactory
{
    private StatFormDTO $form;
    private string|null $templateID;
    private TemplateDTO $template;

    public function __construct(StatisticForm $form)
    {
        $this->form = new StatFormDTO($form->attributes);
        $this->templateID = $form->template;
        $this->template = new TemplateDTO(match((bool) $this->templateID){
            true => TemplateRepository::get($this->templateID)->toArray(),
            false => [
                'name' => Yii::t('entities', 'Динамический отчет без шаблона'),
                'report_id' => $this->form->report,
                'form_datetime' => ReportFormTemplateEntity::REPORT_DATETIME_PERIOD,
                'form_type' => ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC,
                'form_usejobs' => $this->form->dynamic_use_jobs,
                'use_grouptype' => $this->form->dynamic_use_grouptype,
                'use_appg' => $this->form->dynamic_use_appg,
                'table_type' => $this->form->dynamic_form_type,
                'table_rows' => $this->form->dynamic_form_row,
                'table_columns' => $this->form->dynamic_form_column,
                'limit_maxfiles' => 0,
                'limit_maxsavetime' => 0,
            ]
        });
    }

    public function process(): BaseWorker|Response
    {
        $processor = Yii::createObject(match($this->template->form_type) {
            ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC => FromDynamic::class,
            ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE => FromTemplate::class
        }, [$this->form, $this->template]);

        $worker = Yii::createObject(match((bool)$this->template->form_usejobs) {
            true => FormJobWorker::class,
            false => FormDirectWorker::class
        }, [$processor, $this->form]);

        return $worker->run();
    }
}