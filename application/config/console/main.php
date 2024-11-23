<?php

use yii\console\controllers\MigrateController;

use app\bootstrap\ConsoleBootstrap;
use app\events\handlers\ConsoleEventHandler;

$params = array_merge(
    require __DIR__ . '/../params.php',
    require __DIR__ . '/../params-console-local.php',
);

return [
    'id' => 'repostator_console',
    'bootstrap' => [
        'queue',
        ConsoleBootstrap::class,
        ConsoleEventHandler::class,
    ],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => '@migrations',
        ]
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'hostInfo' => '/'
        ]
    ],
    'params' => $params
];