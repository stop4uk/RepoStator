<?php

namespace app\modules\users\events\dispatchers;

use Yii;

use app\jobs\SendEmailJob;
use app\modules\users\{
    events\objects\AuthEvent,
    entities\UserSessionEntity
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\events\dispatchers
 */
final class AuthEventDispatcher
{
    public static function login(AuthEvent $event): void
    {
        $session = new UserSessionEntity();
        $session->save();

        if (Yii::$app->get('settings')->get('auth', 'login_sendEmailAfter')) {
            Yii::$app->queue->push(new SendEmailJob([
                'template' => 'auth/signin',
                'email' => $event->user->email,
                'subject' => Yii::t('emails', 'Вы только что авторизовались'),
                'data' => [
                    'name' => $event->user->shortName,
                    'ip' => $event->request->getUserIP(),
                    'client' => $event->request->getUserAgent(),
                ]
            ]));
        }
    }

    public static function register(AuthEvent $event): void
    {
        $userArray = ['name' => $event->user->shortName];
        $emailParams = [
            'template' => 'auth/signup',
            'subject' => Yii::t('emails', 'Вы успешно зарегистрированы'),
        ];

        if (!Yii::$app->settings->get('auth', 'login_withoutVerification')) {
            $userArray = [
                'name' => $event->user->shortName,
                'account_key' => $event->user->account_key
            ];
            $emailParams = [
                'template' => 'auth/verifyEmailAfterSignup',
                'subject' => Yii::t('emails', 'Подтверждение Email после регистрации'),
            ];
        }

        Yii::$app->queue->push(new SendEmailJob([
            'template' => $emailParams['template'],
            'subject' => $emailParams['subject'],
            'email' => $event->user->email,
            'data' => $userArray
        ]));
    }

    public static function recovery(AuthEvent $event): void
    {
        Yii::$app->queue->push(new SendEmailJob([
            'template' => 'auth/recovery',
            'email' => $event->user->email,
            'subject' => Yii::t('emails', 'Восстановление доступа к системе'),
            'data' => [
                'name' => $event->user->shortName,
                'account_key' => $event->user->account_key,
                'ip' => $event->request->getUserIP(),
            ]
        ]));
    }

    public static function verification(AuthEvent $event): void
    {
        Yii::$app->queue->push(new SendEmailJob([
            'template' => 'auth/verifyEmail',
            'email' => $event->user->email,
            'subject' => Yii::t('emails', 'Повторное подтверждение Email адреса'),
            'data' => [
                'name' => $event->user->shortName,
                'account_key' => $event->user->account_key
            ]
        ]));
    }

    public static function logout(AuthEvent $event): void
    {
        $keys = ['_allowedUserAll', '_allowedUserDeleteAll', '_allowedUserDeleteGroup', '_allowedUserGroup'];
        foreach ($keys as $key) {
            Yii::$app->getCache()->delete($event->user->id . $key);
        }
    }
}