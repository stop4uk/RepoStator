<?php

use creocoder\flysystem\LocalFilesystem;
use app\components\attachedFiles\AttachFileHelper;

return [
    'id' => 'repostator-test',
    'defaultRoute' => 'reports/dashboard',
    'components' => [
        AttachFileHelper::STORAGE_LOCAL => [
            'class' => LocalFilesystem::class,
            'path' => '@root/_storage_test'
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
];