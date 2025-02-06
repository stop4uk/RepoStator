<?php

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\jobs
 */
final class SendEmailJob extends BaseObject implements JobInterface
{
    public string $template;
    public string $email;
    public string $subject;
    public array $data;

    public function execute($queue): void
    {
        $senderEmail = match ((bool) Yii::$app->get('settings')) {
            true => Yii::$app->get('settings')->get('system', 'sender_email') ?? env('MAIL_FROM_EMAIL'),
            false => env('MAIL_FROM_EMAIL')
        };

        $senderName = match ((bool) Yii::$app->get('settings')) {
            true => Yii::$app->get('settings')->get('system', 'sender_email') ?? env('MAIL_FROM_NAME'),
            false => env('MAIL_FROM_NAME')
        };

        try {
            Yii::$app->getMailer()->compose(['html' => $this->template], ['data' => $this->data])
                ->setFrom([$senderEmail => $senderName])
                ->setTo($this->email)
                ->setSubject($this->subject)
                ->send();
        } catch (\Throwable $throwable) {}
    }
}