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
use app\modules\reports\Module as ReportModule;
use app\modules\users\{
    components\Identity,
    components\RbacDbmanager,
    Module as UsersModule
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
    'modules' => [
        'users' => [
            'class' => UsersModule::class,
            'viewPath' => '@resources/views/users',
            'layoutClean' => '@resources/views/layouts/clean'
        ],
        'reports' => [
            'class' => ReportModule::class,
            'viewPath' => '@resources/views/reports',
        ],
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
        'user' => [
            'identityClass' => Identity::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['login'],
            'identityCookie' => [
                'name' => '_identity-' . env('PROJECT_NAME', 'simple'),
            ],
        ],
        'authManager' => RbacDbmanager::class,
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
                '<controller:(dashboard|send|control|statistic)>'               => 'reports/<controller>',
                '<controller:(dashboard|send|control|statistic)>/<action>'      => 'reports/<controller>/<action>',

                '<module:\w+>'                                                  => '<module>/default',
                '<module:\w+>/<action:(index|create|view|edit|delete|enable)>'  => '<module>/default/<action>',

                #Users module
                '<action:(login|logout|register)>'  => 'users/auth/<action>',
                'recovery'                          => 'users/recovery',
                'recovery/<action>'                 => 'users/recovery/<action>',
                'verification'                      => 'users/verification',
                'verification/<action>'             => 'users/verification/<action>',
                'profile'                           => 'users/profile',

                #Reports module
                'reports/<controller:(constant|constantrule|structure|template)>'               => 'reports/<controller>',
                'reports/<controller:(constant|constantrule|structure|template)>/<action:\w+>'  => 'reports/<controller>/<action>',





//                #Админка
//                'admin/groups'                                                              => 'admin_groups',
//                'admin/groups/<action:(create|view|edit|delete|enable|nodeMove)>'           => 'admin_groups/<action>',
//                'admin/groups/type'                 => 'admin_groups_type',
//                'admin/groups/type/<action:\w+>'    => 'admin_groups_type/<action>',
//                'admin/queue'                       => 'admin_queue',
//                'admin/queue/template'              => 'admin_queue_template',
//                'admin/logs'                        => 'admin_logs',
//                'admin/logs/<action:\w+>'           => 'admin_logs/<action>',
//                'admin/settings'                    => 'admin_settings',
//                'admin/users'                       => 'admin_users',
//                'admin/users/<action:\w+>'          => 'admin_users/<action>',
            ],
        ]
    ],
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