<?php

namespace app\modules\users\events\dispatchers;

use Yii;

use app\jobs\SendEmailJob;
use app\modules\users\events\objects\ProfileEvent;

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