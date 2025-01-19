<?php

namespace app\modules\users;

use Yii;
use yii\base\{
    Event,
    Module
};
use yii\web\{
    Application,
    User
};

use app\components\base\BaseModule;
use app\modules\users\components\{
    Identity,
    RbacDbmanager
};

final class UserModule extends Module implements BaseModule
{
    public static function run(): void
    {
        $configPath = dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;

        if (Yii::$app instanceof Application) {
            Yii::$app->setComponents([
                'user' => [
                    'class' => User::class,
                    'identityClass' => Identity::class,
                    'enableAutoLogin' => true,
                    'loginUrl' => ['login'],
                    'identityCookie' => [
                        'name' => '_identity-' . env('PROJECT_NAME', 'simple'),
                    ],
                ],
                'authManager' => RbacDbmanager::class
            ]);
        }

        $routes = require_once $configPath . 'routes.php';
        Yii::$app->getUrlManager()->addRules($routes);

        $events = require_once $configPath . 'events.php';
        foreach ($events as $event) {
            Event::on($event['class'], $event['event'], $event['callable']);
        }

        $logs = require_once $configPath . 'logs.php';
        foreach ($logs as $log) {
            Yii::$app->getLog()->targets[] = $log;
        }
    }
}