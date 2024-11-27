<?php

use yii\console\controllers\MigrateController;
use yii\helpers\ArrayHelper;

use app\components\{
    bootstrap\ConsoleBootstrap,
    events\handlers\ConsoleEventHandler
};

$params = array_merge(
    require __DIR__ . '/_params_common.php',
    require __DIR__ . '/_params_console.php',
);

$config = [
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

return ArrayHelper::merge(
    require __DIR__ . '/common.php',
    $config
);