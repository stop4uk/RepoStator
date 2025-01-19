<?php

namespace stop4uk\users\events\dispatchers;

use Yii;

use app\helpers\EmailHelper;
use stop4uk\users\events\objects\UserEvent;
use stop4uk\users\helpers\UserHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\dispatchers
 */
final class UserEventDispatcher
{
    public static function add(UserEvent $event): void
    {
        EmailHelper::send(
            template: 'notifications/userAdd',
            toEmail: $event->user['email'],
            subject: Yii::t('emails', 'Вы были добавлены'),
            data: [
                'name' => UserHelper::getShortName([
                    'lastname' => $event->user['lastname'],
                    'firstname' => $event->user['firstname'],
                    'middlename' => $event->user['middlename']
                ]),
                'password' => $event->user['password']
            ]
        );
    }

    public static function change(UserEvent $event): void
    {
        $changeArray = [];

        if ( $event->user['password'] ) {
            $changeArray['password'] = $event->user['password'];
        }

        if ( $event->user['group'] && $event->user['group'] != $event->user['hasGroup'] ) {
            $changeArray['group'] = Yii::$app->getUser()->getIdentity()->groups[$event->user['group']];
        }

        if ( $event->user['email'] != $event->userEntity['email'] ) {
            $changeArray['email'] = $event->user['email'];
            $changeArray['account_key'] = $event->userEntity['account_key'];
        }

        if ( $event->user['account_status'] != $event->userEntity['account_status'] ) {
            $changeArray['account_status'] = UserHelper::statusName($event->user['account_status']);
        }

        if ( $changeArray ) {
            $changeArray['name'] = $event->userEntity['sName'];

            EmailHelper::send(
                template: 'notifications/userChange',
                toEmail: $event->userEntity['email'],
                subject: Yii::t('emails', 'Ваша учетная запись изменена'),
                data: $changeArray
            );
        }
    }

    public static function delete(UserEvent $event): void
    {
        EmailHelper::send(
            template: 'notifications/userDelete',
            toEmail: $event->userEntity->email,
            subject: Yii::t('emails', 'Ваша учетная запись удалена'),
            data: [
                'name' => $event->userEntity->shortName,
            ]
        );
    }
}