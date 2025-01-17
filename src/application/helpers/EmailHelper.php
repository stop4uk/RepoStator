<?php

namespace app\helpers;

use Yii;
use yii\base\Exception;

use app\jobs\SendEmailJob;
use yii\base\InvalidConfigException;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers
 */
final class EmailHelper
{
    public static function send(
        string $template,
        string $toEmail,
        string $subject = '',
        array $data = []
    ): void
    {
        try {
            if (Yii::$app->get('queue')) {
                Yii::$app->get('queue')->push(new SendEmailJob([
                    'template' => $template,
                    'email' => $toEmail,
                    'subject' => $subject,
                    'data' => $data
                ]));
            } else {
                self::sendByNative($template, $toEmail, $subject, $data);
            }
        } catch (Exception $e) {}
    }

    /**
     * @throws InvalidConfigException
     */
    public static function sendByNative(
        string $template,
        string $toEmail,
        string $subject,
        array $data
    ): void {
        $senderEmail = match((bool) Yii::$app->get('settings')) {
            true => Yii::$app->get('settings')->get('system', 'sender_email') ?? env('MAIL_FROM_EMAIL'),
            false => env('MAIL_FROM_EMAIL')
        };

        $senderName = match((bool) Yii::$app->get('settings')) {
            true => Yii::$app->get('settings')->get('system', 'sender_email') ?? env('MAIL_FROM_NAME'),
            false => env('MAIL_FROM_NAME')
        };

        if ($senderName && $senderEmail) {
            Yii::$app->getMailer()->compose(['html' => $template], ['data' => $data])
                ->setFrom([$senderEmail => $senderName])
                ->setTo($toEmail)
                ->setSubject($subject)
                ->send();

            return;
        }

        Yii::error('Email send error. See app\helpers\EmailHelper', 'application');
    }
}