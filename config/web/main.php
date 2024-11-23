<?php

use app\bootstrap\WebBootstrap;
use app\events\handlers\WebEventHandler;
use app\components\Identity;
use app\rbac\RbacDbmanager;

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
        WebBootstrap::class,
        WebEventHandler::class,
    ],
    'components' => [
        'user' => [
            'identityClass' => Identity::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['login'],
            'identityCookie' => [
                'name' => '_identity-repostator',
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
        'urlManager' => require __DIR__ . '/routes.php'
    ],
    'params' => $params,
];