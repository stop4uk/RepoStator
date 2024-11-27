<?php

namespace app\components\events\dispatchers;

use Yii;

use app\components\events\objects\StatisticEvent;
use app\jobs\SendEmailJob;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\dispatchers
 */
final class StatisticEventDispatcher
{
    public static function complete(StatisticEvent $event): void
    {
        Yii::$app->queue->push(new SendEmailJob([
            'template' => 'notifications/templateFormComplete',
            'email' => $event->jobEntity->user->email,
            'subject' => Yii::t('emails', 'Отчет успешно сформирован'),
            'data' => [
                'job' => $event->jobEntity,
                'template' => $event->template,
                'period' => $event->period
            ]
        ]));
    }

    public static function error(StatisticEvent $event): void
    {
        Yii::$app->queue->push(new SendEmailJob([
            'template' => 'notifications/templateFormError',
            'email' => $event->jobEntity->user->email,
            'subject' => Yii::t('emails', 'Ошибка формирования отчета'),
            'data' => [
                'job' => $event->jobEntity,
                'template' => $event->template,
            ]
        ]));
    }
}