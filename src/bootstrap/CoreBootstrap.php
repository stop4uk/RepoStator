<?php

namespace app\bootstrap;

use yii\base\BootstrapInterface;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\bootstrap
 */
final class CoreBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $this->setLanguage($app);
    }

    private function setLanguage($app): void
    {
        /**
         * Так как, после инициализации приложения таблицы с настройками еще нет - оборачиваем в try/catch,
         * чтобы все прошло без ошибок
         */
        try {
            $app->language = $app->settings->get('system', 'app_language');
            $app->formatter->dateFormat = $app->settings->get('system', 'app_language_date');
            $app->formatter->datetimeFormat = $app->settings->get('system', 'app_language_dateTime');
        } catch (\Throwable $throwable) {}
    }
}
