<?php

namespace stop4uk\users\events\dispatchers;

use Yii;

use app\helpers\EmailHelper;
use stop4uk\users\events\objects\AuthEvent;
use stop4uk\users\entities\user\{
    UserRightEntity,
    UserSessionEntity
};

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

        if ( Yii::$app->get('settings')->get('auth', 'login_sendEmailAfter') ) {
            EmailHelper::send(
                template: 'auth/signin',
                toEmail: $event->user->email,
                subject: Yii::t('emails', 'Вы только что авторизовались'),
                data: [
                    'name' => $event->user->shortName,
                    'ip' => $event->request->getUserIP(),
                    'client' => $event->request->getUserAgent(),
                ]
            );
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

        EmailHelper::send(
            template: $emailParams['template'],
            toEmail: $event->user->email,
            subject: $emailParams['subject'],
            data: $userArray
        );

        $rightModel = new UserRightEntity();
        $rightModel->item_name = 'role_dataMain';
        $rightModel->user_id = $event->user->id;
        $rightModel->created_at = time();
        $rightModel->created_uid = 1;
        $rightModel->save(false);
    }

    public static function recovery(AuthEvent $event): void
    {
        EmailHelper::send(
            template: 'auth/recovery',
            toEmail: $event->user->email,
            subject: Yii::t('emails', 'Восстановление доступа к системе'),
            data: [
                'name' => $event->user->shortName,
                'account_key' => $event->user->account_key,
                'ip' => $event->request->getUserIP(),
            ]
        );
    }

    public static function verification(AuthEvent $event): void
    {
        EmailHelper::send(
            template: 'auth/verifyEmail',
            toEmail: $event->user->email,
            subject: Yii::t('emails', 'Повторное подтверждение Email адреса'),
            data: [
                'name' => $event->user->shortName,
                'account_key' => $event->user->account_key
            ]
        );
    }

    public static function logout(AuthEvent $event): void
    {
        $keys = ['_allowedUserAll', '_allowedUserDeleteAll', '_allowedUserDeleteGroup', '_allowedUserGroup'];
        foreach ($keys as $key) {
            Yii::$app->getSession()->remove($event->user->id . $key);
        }
    }
}