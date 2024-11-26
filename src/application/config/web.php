<?php

use app\components\{bootstrap\WebBootstrap, events\handlers\WebEventHandler};
use components\Identity;
use components\rbac\RbacDbmanager;
use yii\{debug\Module as DebugModule, gii\Module as GiiModule};
use yii\helpers\ArrayHelper;
use yii\queue\debug\Panel;
use yii\web\Cookie;

$params = array_merge(
    require __DIR__ . '/_params_common.php',
    require __DIR__ . '/params_web.php',
);

$config = [
    'defaultRoute' => 'dashboard',
    'aliases' => [
        '@web' => '@root/public',
        '@assets' => '@root/public/assets',
    ],
    'viewPath' => '@resources/views',
    'bootstrap' => [
        WebBootstrap::class,
        WebEventHandler::class,
    ],
    'components' => [
        'user' => [
            'identityClass' => Identity::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['login'],
            'identityCookie' => [
                'name' => '_identity-' . env('PROJECT_NAME'),
            ],
        ],
        'authManager' => [
            'class' => RbacDbmanager::class,
        ],
        'errorHandler' => [
            'errorAction' => 'error/fault',
        ],
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
                'kartik\select2\Select2Asset' => [
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
            'timeout' => 24 * 30 * 3600,
            'name' => env('PROJECT_NAME') . '_session',
            'useCookies'   => true,
            'cookieParams' => [
                'httponly' => true,
                'secure' => true,
                'sameSite' => Cookie::SAME_SITE_STRICT,
            ],
        ],
        'urlManager' => require __DIR__ . '/web_routes.php'
    ],
    'params' => $params,
];

if (bool(getenv('YII_GII'))) {
    $config['modules']['gii'] = [
        'class' => GiiModule::class,
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
}

if (bool(getenv('YII_DEBUG'))) {
    $config['modules']['debug'] = [
        'class' => DebugModule::class,
        'panels' => [
            'queue' => Panel::class,
        ],
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'debug';
}

return ArrayHelper::merge($config, require __DIR__ . '/common.php');