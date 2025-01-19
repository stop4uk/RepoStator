<?php

namespace stop4uk\users\events\dispatchers;

use Yii;

use app\helpers\EmailHelper;
use stop4uk\users\events\objects\ProfileEvent;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\dispatchers
 */
final class ProfileEventDispatcher
{
    public static function changeEmail(ProfileEvent $event): void
    {
        EmailHelper::send(
            template: 'profile/changeEmail',
            toEmail: $event->email,
            subject: Yii::t('emails', 'Запрос изменения Email адреса'),
            data: [
                'name' => $event->userName,
                'email' => $event->email,
                'key' => $event->key
            ]
        );
    }
}