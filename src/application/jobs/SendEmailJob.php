<?php

namespace app\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;

use app\helpers\EmailHelper;

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
        EmailHelper::sendByNative($this->template, $this->email, $this->subject, $this->data);
    }
}