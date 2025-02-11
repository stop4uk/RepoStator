<?php

use yii\web\{
    Cookie,
    UrlNormalizer
};
use yii\helpers\ArrayHelper;
use yii\queue\debug\Panel;
use yii\{
    debug\Module as DebugModule,
    gii\Module as GiiModule
};

use app\components\{
    bootstrap\WebBootstrap,
    events\WebEventHandler
};

use app\modules\users\{
    components\Identity,
    components\rbac\PhpDBRbacManager,
};

$params = array_merge(
    require __DIR__ . '/_params_common.php',
    require __DIR__ . '/_params_web.php',
);

$config = [
    'defaultRoute' => 'reports/dashboard',
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
        'errorHandler' => [
            'errorAction' => 'error/fault',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@resources'
            ],
        ],
        'user' => [
            'identityClass' => Identity::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['login'],
            'identityCookie' => [
                'name' => '_identity-' . env('PROJECT_NAME', 'simple'),
            ],
        ],
        'authManager' => PhpDBRbacManager::class,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => UrlNormalizer::class,
                'normalizeTrailingSlash' => true,
                'collapseSlashes' => true,
            ],
            'rules' => [
                'profile'                                                                       => 'users/profile',
                'profile/<action:\w+>'                                                          => 'users/profile/<action>',
                'recovery'                                                                      => 'users/recovery',
                'recovery/<action:\w+>'                                                         => 'users/recovery/<action>',
                'verification'                                                                  => 'users/verification',
                'verification/<action:\w+>'                                                     => 'users/verification/<action>',
                '<action:(login|logout|register)>'                                              => 'users/auth/<action>',
                '<controller:(dashboard|send|control|statistic)>'                               => 'reports/<controller>',
                '<controller:(dashboard|send|control|statistic)>/<action>'                      => 'reports/<controller>/<action>',

                '<module:\w+>'                                                                  => '<module>/default',
                '<module:\w+>/<action:(index|create|view|edit|delete|enable)>'                  => '<module>/default/<action>',

                'reports/<controller:(constant|constantrule|structure|template)>'               => 'reports/<controller>',
                'reports/<controller:(constant|constantrule|structure|template)>/<action:\w+>'  => 'reports/<controller>/<action>',

                'admin/groups'                                                                  => 'admin/groups/default',
                'admin/groups/<action:(create|view|edit|delete|enable|map|nodeMove)>'           => 'admin/groups/default/<action>',
                'admin/queue'                                                                   => 'admin/queue/default',
            ],
        ]
    ],
    'params' => $params,
];

if ((bool)env('YII_GII', false)) {
    $config['modules']['gii'] = [
        'class' => GiiModule::class,
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
}

if ((bool)env('YII_DEBUG', false)) {
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