<?php

use yii\console\controllers\{
    MigrateController,
    FixtureController
};
use yii\helpers\ArrayHelper;

use app\components\{
    bootstrap\ConsoleBootstrap,
    events\ConsoleEventHandler
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
            'migrationNamespaces' => [
                'app\modules\users\migrations',
                'app\modules\reports\migrations'
            ],
        ],
        'fixture' => [
            'class' => FixtureController::class,
            'namespace' => 'root\tests\fixtures',
        ],
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
    require 'common.php',
    $config
);