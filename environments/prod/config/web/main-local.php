<?php

return [
    'components' => [
        'authManager' => [
            'cache' => 'cache'
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => false,
            'forceCopy' => false,
            'basePath' => '@assets',
            'bundles' => [
                'kartik\select2\Select2Asset' => [
                    'sourcePath' => '@resources',
                    'css' => ['assets/components/select2/css/select2.min.css'],
                    'js' => ['assets/components/select2/js/select2.js'],
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
                'sameSite' => \yii\web\Cookie::SAME_SITE_STRICT,
            ],
        ],
    ]
];