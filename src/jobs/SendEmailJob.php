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

    public function execute($queue)
    {
        try {
            Yii::$app->mailer->compose(['html' => $this->template], ['data' => $this->data])
                ->setFrom([Yii::$app->settings->get('system', 'sender_email') => Yii::$app->settings->get('system', 'sender_name')])
                ->setTo($this->email)
                ->setSubject($this->subject)
                ->send();
        } catch (\Exception $e) { Yii::warning($e->getMessage(), 'Queue'); }
    }
}