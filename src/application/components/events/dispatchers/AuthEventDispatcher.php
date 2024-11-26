<?php

namespace app\components\events\dispatchers;

use app\components\events\objects\AuthEvent;
use app\jobs\SendEmailJob;
use entities\user\{UserSessionEntity};
use entities\user\UserRightEntity;
use Yii;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\dispatchers
 */
final class AuthEventDispatcher
{
    public static function login(AuthEvent $event): void
    {
        $session = new UserSessionEntity();
        $session->save();

        if ( Yii::$app->settings->get('auth', 'login_sendEmailAfter') ) {
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

        if ( !Yii::$app->settings->get('auth', 'login_withoutVerification') ) {
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

        $rightModel = new UserRightEntity();
        $rightModel->item_name = 'role_dataMain';
        $rightModel->user_id = $event->user->id;
        $rightModel->created_at = time();
        $rightModel->created_uid = 1;
        $rightModel->save(false);
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