<?php

namespace app\components\bootstrap;

use yii\base\BootstrapInterface;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\components\bootstrap
 */
final class WebBootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        try {
            $app->getSession()->timeout = $app->settings->get('auth', 'login_duration');
        } catch (\Throwable $throwable) {}
    }
}
