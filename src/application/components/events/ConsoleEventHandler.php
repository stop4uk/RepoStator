<?php

namespace app\components\events;

use yii\base\{
    BootstrapInterface,
    Event
};
use yii\queue\{
    ExecEvent,
    Queue
};

use app\modules\reports\{
    entities\ReportFormJobEntity,
    events\StatisticEventDispatcher,
    jobs\FormTemplateJob
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\handlers
 */
final class ConsoleEventHandler implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        Event::on(ReportFormJobEntity::class, ReportFormJobEntity::EVENT_AFTER_COMPLETE, [StatisticEventDispatcher::class, 'complete']);
        Event::on(ReportFormJobEntity::class, ReportFormJobEntity::EVENT_AFTER_ERROR, [StatisticEventDispatcher::class, 'error']);

        Event::on(Queue::class, Queue::EVENT_AFTER_ERROR, function (ExecEvent $event) {
            if ($event->job instanceof FormTemplateJob) {
                (ReportFormJobEntity::find()
                    ->with(['user', 'template'])
                    ->where(['job_id' => $event->job->jobID])
                    ->limit(1)
                    ->one())->setError();
            }
        });
    }
}