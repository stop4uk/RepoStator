<?php

namespace app\components\bootstrap;

use yii\base\BootstrapInterface;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\bootstrap
 */
final class WebBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->session->timeout = $app->settings->get('auth', 'login_duration');
    }
}
