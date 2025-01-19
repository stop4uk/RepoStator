<?php

namespace stop4uk\users;

use Yii;
use yii\base\Event;
use yii\web\{
    Application,
    User
};

use stop4uk\users\components\{
    Identity,
    RbacDbmanager
};

final class Module extends \yii\base\Module
{
    public $layout;
    public $layoutClean;
    public int|null $leftMenuSort = null;
    public int|null $topMenuSort = 1;

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