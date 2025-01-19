<?php

namespace app\modules\users;

use app\modules\users\entities\UserEntity;
use Yii;
use yii\base\{
    Event,
    Module
};
use yii\web\Application;

use app\modules\users\components\{
    Identity,
    RbacDbmanager
};
use yii\web\User;

final class UserModule extends Module
{
    public static function run(): void
    {
        $configPath = dirname(__FILE__, 1) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        $eventsFilePath = $configPath . 'events.php';
        $routesFilePath = $configPath . 'routes.php';
        $logsFilePath = $configPath . match((bool)env('YII_DEBUG')) {
            true => 'logs_file.php',
            false => 'logs_db.php'
        };

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

        if (file_exists($routesFilePath)) {
            $routes = require_once $routesFilePath;
            Yii::$app->getUrlManager()->addRules($routes);
        }

        if (file_exists($logsFilePath)) {
            $logs = require_once $logsFilePath;
            foreach ($logs as $log) {
                Yii::$app->getLog()->targets[] = $log;
            }
        }

        if (file_exists($eventsFilePath)) {
            $events = require_once $eventsFilePath;
            foreach ($events as $event) {
                Event::on($event['class'], $event['event'], $event['callable']);
            }
        }
    }
}