<?php

namespace app\events\handlers;

use Yii;
use yii\base\{
    BootstrapInterface,
    Event
};
use yii\queue\{
    Queue,
    ExecEvent
};

use app\events\dispatchers\StatisticEventDispatcher;
use app\entities\report\ReportFormJobEntity;
use app\jobs\FormTemplateJob;

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