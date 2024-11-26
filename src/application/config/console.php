<?php

use yii\console\controllers\MigrateController;

use app\components\{
    bootstrap\ConsoleBootstrap,
    events\handlers\ConsoleEventHandler
};

$params = array_merge(
    require __DIR__ . '/_params_common.php',
    require __DIR__ . '/_params_console.php',
);

return [
    'bootstrap' => [
        ConsoleBootstrap::class,
        ConsoleEventHandler::class,
    ],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
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