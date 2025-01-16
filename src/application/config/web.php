<?php

use yii\web\{
    Cookie,
    UrlNormalizer
};
use yii\queue\debug\Panel;
use yii\{
    debug\Module as DebugModule,
    gii\Module as GiiModule
};
use yii\helpers\ArrayHelper;
use kartik\select2\Select2Asset;

use app\components\{
    bootstrap\WebBootstrap,
    events\WebEventHandler
};

$params = array_merge(
    require __DIR__ . '/_params_common.php',
    require __DIR__ . '/_params_web.php',
);

$config = [
    'aliases' => [
        '@web' => '@root/public',
        '@assets' => '@root/public/assets',
    ],
    'viewPath' => '@resources/views',
    'defaultRoute' => 'site',
    'bootstrap' => [
        WebBootstrap::class,
        WebEventHandler::class,
    ],
    'components' => [
        'view' => [
            'theme' => [
                'basePath' => '@resources'
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
            'forceCopy' => true,
            'basePath' => '@assets',
            'bundles' => [
                Select2Asset::class => [
                    'sourcePath' => '@resources',
                    'css' => ['assets/components/select2/css/select2.css'],
                    'js' => ['assets/components/select2/js/select2.full.js'],
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-' . env('PROJECT_NAME'),
            'cookieValidationKey' => env('YII_COOKIE_VALIDATION_KEY'),
        ],
        'session' => [
            'timeout' => env('YII_DURATION_SESSION'),
            'name' => env('PROJECT_NAME') . '_session',
            'useCookies'   => true,
            'cookieParams' => [
                'httponly' => true,
                'secure' => true,
                'sameSite' => Cookie::SAME_SITE_STRICT,
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/fault',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => UrlNormalizer::class,
                'normalizeTrailingSlash' => true,
                'collapseSlashes' => true,
            ],
        ],
    ],
    'on beforeAction' => function ($event) {
        $controllerID = $event->action->controller->id;
        $maintenanceMode = (bool)Yii::$app->settings->get('system', 'app_maintenance');

        if (
            $maintenanceMode
            && $controllerID != 'offline'
        ) {
            return Yii::$app->getResponse()->redirect(["/offline"]);
        }
    },
    'params' => $params,
];

if ((bool)getenv('YII_GII')) {
    $config['modules']['gii'] = [
        'class' => GiiModule::class,
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
}

if ((bool)getenv('YII_DEBUG')) {
    $config['modules']['debug'] = [
        'class' => DebugModule::class,
        'panels' => [
            'queue' => Panel::class,
        ],
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'debug';
}

return ArrayHelper::merge(
    require 'common.php',
    $config
);