<?php

namespace app\components\bootstrap;

use yii\base\BootstrapInterface;
use yii\base\Event;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\bootstrap
 */
final class CommonBootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $this->setModules($app);
        $this->setLanguage($app);
    }

    private function setLanguage($app): void
    {
        try {
            $app->language = $app->settings->get('system', 'app_language');
            $app->formatter->dateFormat = $app->settings->get('system', 'app_language_date');
            $app->formatter->datetimeFormat = $app->settings->get('system', 'app_language_dateTime');
        } catch (\Throwable $throwable) {}
    }

    private function setModules($app): void
    {
        $path = dirname(__DIR__, 2) . '/modules';
        $modules = scandir($path);
        if (!$modules) {
            return;
        }

        foreach($modules as $module) {
            $configPath = $path . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'setup.php';
            if (file_exists($configPath)) {
                $settings = require_once $configPath;
                $app->setModule($module, $settings);
                if($app->getModule($module)->hasMethod('run')) {
                    $settings['class']::run();
                }
            }
        }
    }
}
