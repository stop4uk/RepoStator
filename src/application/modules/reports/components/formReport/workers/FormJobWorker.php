<?php

namespace app\modules\reports\components\formReport\workers;

use Yii;
use yii\helpers\Url;

use app\helpers\CommonHelper;
use app\modules\reports\{
    components\formReport\base\BaseWorker,
    components\formReport\jobs\FormTemplateJob,
    entities\ReportFormJobEntity
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\fomrReport\workers
 */
final class FormJobWorker extends BaseWorker
{
    public function run()
    {
        $jobID = Yii::$app->getSecurity()->generateRandomString(12);
        $saveModel = new ReportFormJobEntity([
            'job_id' => $jobID,
            'report_id' => $this->form->report,
            'template_id' => $this->form->template,
            'form_period' => $this->form->period
        ]);

        if (CommonHelper::saveAttempt($saveModel, 'Reports.Jobs')) {
            Yii::$app->queue->push(new FormTemplateJob([
                'processor' => $this->processor,
                'jobID' => $jobID
            ]));

            Yii::$app->getSession()->setFlash('success', Yii::t('notifications', 'Запрос на построение отчета ' .
                'успешно добавлен и, поставлен в очередь на выполнение'));
            return Yii::$app->controller->redirect(Url::to(['/statistic']));
        }
    }
}
