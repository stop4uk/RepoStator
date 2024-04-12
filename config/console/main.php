<?php

$params = array_merge(
    require __DIR__ . '/../params.php',
    require __DIR__ . '/../params-console-local.php',
);

return [
    'id' => 'repostator_console',
    'bootstrap' => [
        'queue',
        \app\bootstrap\ConsoleBootstrap::class,
        \app\events\handlers\ConsoleEventHandler::class,
    ],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
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