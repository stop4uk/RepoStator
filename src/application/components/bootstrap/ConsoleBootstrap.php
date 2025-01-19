<?php

namespace app\components\bootstrap;

use yii\base\BootstrapInterface;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\bootstrap
 */
final class ConsoleBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $this->updateUrlManager($app);
    }

    private function updateUrlManager($app): void
    {
        /**
         * Так как, после инициализации приложения таблицы с настройками еще нет - оборачиваем в try/catch,
         * чтобы все прошло без ошибок
         */
        try {
            $app->getUrlManager()->scriptUrl = $app->settings->get('system', 'app_hostname', 'http://localhost');
        } catch(\Throwable $throwable){}
    }
}
