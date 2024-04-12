<?php

$params = array_merge(
    require __DIR__ . '/../params.php',
    require __DIR__ . '/../params-web-local.php',
);

return [
    'id' => 'repostator',
    'defaultRoute' => 'dashboard',
    'sourceLanguage' => 'ru',
    'aliases' => [
        '@web' => '@root/public',
        '@assets' => '@root/public/assets',
    ],
    'viewPath' => '@resources/views',
    'bootstrap' => [
        \app\bootstrap\WebBootstrap::class,
        \app\events\handlers\WebEventHandler::class,
    ],
    'components' => [
        'user' => [
            'identityClass' => \app\components\Identity::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['login'],
            'identityCookie' => [
                'name' => '_identity-repostator',
            ],
        ],
        'authManager' => [
            'class' => \app\rbac\RbacDbmanager::class,
        ],
        'errorHandler' => [
            'errorAction' => 'error/fault',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@resources'
            ],
        ],
        'urlManager' => require __DIR__ . '/routes.php'
    ],
    'params' => $params,
];