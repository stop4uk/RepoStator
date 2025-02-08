<?php

use yii\db\Connection;
use Symfony\Component\Mailer;

use creocoder\flysystem\LocalFilesystem;
use app\components\attachedFiles\AttachFileHelper;

return [
    'id' => 'repostator-test',
    'components' => [
        AttachFileHelper::STORAGE_LOCAL => [
            'class' => LocalFilesystem::class,
            'path' => '@root/_storage_test'
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=' . env('TESTDB_HOST', 'mysqltest') . ';port=' . env('TESTDB_PORT', '3316') . ';dbname=' . env('TESTDB_NAME', 'repostator_test'),
            'username' => env('TESTDB_USER', 'root'),
            'password' => env('TESTDB_PASS', ''),
            'charset' => 'utf8',
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 0,
            'schemaCache' => null,
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@resources/emails',
            'useFileTransport' => false,
            'transport' => [
                'scheme' => env('TESTMAIL_SCHEME', 'smtp'),
                'host' => env('TESTMAIL_HOST', 'mailhog'),
                'username' => env('TESTMAIL_USERNAME', ''),
                'password' => env('TESTMAIL_PASSWORD', ''),
                'port' => (int)env('TESTMAIL_PORT', 1025),
            ],
        ],
    ],
];