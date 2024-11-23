<?php

use yii\web\Cookie;
use yii\queue\debug\Panel;
use yii\gii\Module as GiiModule;
use yii\debug\Module as DebugModule;

return [
    'bootstrap' => [
        'debug',
        'gii'
    ],
    'modules' => [
        'debug' => [
            'class' => DebugModule::class,
            'panels' => [
                'queue' => Panel::class,
            ],
            'allowedIPs' => ['*'],
        ],
        'gii' => [
            'class' => GiiModule::class,
            'allowedIPs' => ['*'],
        ],
    ],
    'components' => [
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
            'csrfParam' => '_csrf-repostator',
            'cookieValidationKey' => 'xwC3RtK2TB2rAx8XskL9JweXN3rZsY1b',
        ],
        'session' => [
            'timeout' => 24 * 30 * 3600,
            'name' => 'repostator_session',
            'useCookies'   => true,
            'cookieParams' => [
                'httponly' => true,
                'secure' => true,
                'sameSite' => Cookie::SAME_SITE_STRICT,
            ],
        ],
    ]
];