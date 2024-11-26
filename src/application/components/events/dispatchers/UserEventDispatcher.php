<?php

namespace app\components\events\dispatchers;

use app\components\events\objects\UserEvent;
use app\helpers\user\UserHelper;
use app\jobs\SendEmailJob;
use Yii;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\dispatchers
 */
final class UserEventDispatcher
{
    public static function add(UserEvent $event): void
    {
        Yii::$app->queue->push(new SendEmailJob([
            'template' => 'notifications/userAdd',
            'email' => $event->user['email'],
            'subject' => Yii::t('emails', 'Вы были добавлены'),
            'data' => [
                'name' => UserHelper::getShortName([
                    'lastname' => $event->user['lastname'],
                    'firstname' => $event->user['firstname'],
                    'middlename' => $event->user['middlename']
                ]),
                'password' => $event->user['password']
            ]
        ]));
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

            Yii::$app->queue->push(new SendEmailJob([
                'template' => 'notifications/userChange',
                'email' => $event->userEntity['email'],
                'subject' => Yii::t('emails', 'Ваша учетная запись изменена'),
                'data' => $changeArray
            ]));
        }
    }

    public static function delete(UserEvent $event): void
    {
        Yii::$app->queue->push(new SendEmailJob([
            'template' => 'notifications/userDelete',
            'email' => $event->userEntity->email,
            'subject' => Yii::t('emails', 'Ваша учетная запись удалена'),
            'data' => [
                'name' => $event->userEntity->shortName,
            ]
        ]));
    }
}