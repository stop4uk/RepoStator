<?php

use creocoder\flysystem\LocalFilesystem;
use app\components\attachedFiles\AttachFileHelper;

return [
    'id' => 'repostator-test',
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