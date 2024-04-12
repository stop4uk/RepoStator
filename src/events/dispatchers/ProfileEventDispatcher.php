<?php

namespace app\events\dispatchers;

use Yii;

use app\events\objects\ProfileEvent;
use app\jobs\SendEmailJob;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\dispatchers
 */
final class ProfileEventDispatcher
{
    public static function changeEmail(ProfileEvent $event): void
    {
        Yii::$app->queue->push(new SendEmailJob([
            'template' => 'profile/changeEmail',
            'email' => $event->email,
            'subject' => Yii::t('emails', 'Запрос изменения Email адреса'),
            'data' => [
                'name' => $event->userName,
                'email' => $event->email,
                'key' => $event->key
            ]
        ]));
    }
}