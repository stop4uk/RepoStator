<?php

namespace app\useCases\reports\components\processors;

use Yii;
use yii\helpers\Url;

use app\helpers\CommonHelper;
use app\useCases\reports\{
    components\base\BaseProcessorInterface,
    entities\ReportFormJobEntity,
    entities\ReportFormTemplateEntity,
    forms\StatisticForm,
    jobs\FormTemplateJob
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\processors
 */
class ToFileByJobProcessor implements BaseProcessorInterface
{
    private $form;
    private $template;
    private $jobID;

    public function __construct(
        StatisticForm $form,
        ReportFormTemplateEntity $template
    )
    {
        $this->form = $form;
        $this->template = $template;
        $this->jobID = Yii::$app->getSecurity()->generateRandomString(12);
    }

    public function run()
    {
        $saveModel = new ReportFormJobEntity();
        $saveModel->job_id = $this->jobID;
        $saveModel->report_id = $this->form->report;
        $saveModel->template_id = $this->form->template;
        $saveModel->form_period = $this->form->period;

        if ( CommonHelper::saveAttempt($saveModel, 'Reports.Jobs') ) {
            Yii::$app->queue->push(new FormTemplateJob([
                'form' => $this->form,
                'template' => $this->template,
                'jobID' => $this->jobID
            ]));

            Yii::$app->getSession()->setFlash('success', Yii::t('notifications', 'Запрос на построение отчета ' .
                'успешно добавлен и, поставлен в очередь на выполнение'));
            return Yii::$app->controller->redirect(Url::to(['/statistic']));
        }
    }
}