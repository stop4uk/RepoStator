<?php

namespace app\components\bootstrap;

use Yii;
use yii\base\BootstrapInterface;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\components\bootstrap
 */
final class CommonBootstrap implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $this->setLanguage($app);
    }

    private function setLanguage($app): void
    {
        try {
            $app->language = $app->settings->get('system', 'app_language');
            $app->getFormatter()->dateFormat = $app->settings->get('system', 'app_language_date');
            $app->getFormatter()->datetimeFormat = $app->settings->get('system', 'app_language_dateTime');

            if (!is_dir(Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . env('YII_FILES_TEMPORARY_PATH', 'tmpFiles'))) {
                mkdir(Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . env('YII_FILES_TEMPORARY_PATH', 'tmpFiles'), 777);
            }
        } catch (\Throwable $throwable) {}
    }
}
