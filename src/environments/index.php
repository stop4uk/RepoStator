<?php

return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'storage',
            'public/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'config/web/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'storage',
            'public/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'config/web/main-local.php',
        ],
    ],
];
