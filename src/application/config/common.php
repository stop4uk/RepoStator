<?php

use yii\caching\FileCache;
use yii\db\Connection;
use yii\i18n\PhpMessageSource;
use yii\mutex\MysqlMutex;
use yii\queue\{
    db\Queue,
    LogBehavior
};
use yii\symfonymailer\Mailer;
use creocoder\flysystem\LocalFilesystem;

use app\components\{
    attachedFiles\AttachFileHelper,
    bootstrap\CommonBootstrap,
    events\CommonEventHandler,
    settings\Settings
};
use app\modules\{
    users\Module as UsersModule,
    reports\Module as ReportsModule,
    admin\Module as AdminModule
};

return [
    'id' => env('PROJECT_NAME'),
    'name' => env('YII_APP_NAME'),
    'sourceLanguage' => env('YII_APP_SOURCE_LANG'),
    'basePath' => dirname(__DIR__) . '/../',
    'runtimePath' => dirname(__DIR__) . '/../runtime',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@root' => dirname(__DIR__, 2),
        '@app' => dirname(__DIR__) . '/',
        '@runtime' => '@root/runtime',
        '@resources' => '@root/resources',
        '@downloads' => '@runtime/files',
        '@uploads' => '@root/' . env('YII_UPLOADS_PATH_LOCAL'),
        '@templates' => '@uploads/' . env('YII_UPLOADS_PATH_LOCAL_TEMPLATE'),
    ],
    'bootstrap' => [
        'log',
        'queue',
        CommonBootstrap::class,
        CommonEventHandler::class,
    ],
    'modules' => [
        'users' => [
            'class' => UsersModule::class,
            'viewPath' => '@resources/views/users',
            'layoutClean' => '@resources/views/layouts/clean'
        ],
        'reports' => [
            'class' => ReportsModule::class,
            'viewPath' => '@resources/views/reports',
        ],
        'admin' => [
            'class' => AdminModule::class,
            'viewPath' => '@resources/views/admin',
        ],
    ],
    'components' => [
        AttachFileHelper::STORAGE_LOCAL => [
            'class' => LocalFilesystem::class,
            'path' => '@uploads'
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => '@resources/messages',
                    'forceTranslation' => true,
                ],
            ],
        ],
        'formatter' => [
            'thousandSeparator' => '.',
            'decimalSeparator' => '.',
            'nullDisplay' => '',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 0,
            ]
        ],
        'cache' => [
            'class' => FileCache::class,
            'defaultDuration' => (int)env('YII_DURATION_CACHE')
        ],
        'queue' => [
            'class' => Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => MysqlMutex::class,
            'as log' => LogBehavior::class,
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@resources/emails',
            'useFileTransport' => false,
            'transport' => [
                'scheme' => env('MAIL_SCHEME'),
                'host' => env('MAIL_HOST'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'port' => env('MAIL_PORT'),
            ],
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=' . env('DB_HOST') . ';port=' . env('DB_PORT') . ';dbname=' . env('DB_NAME'),
            'username' => 'root',
            'password' => env('DB_PASS'),
            'charset' => 'utf8',
            'enableSchemaCache' => (bool)env('YII_DEBUG'),
            'schemaCacheDuration' => match ((bool)env('YII_DEBUG')) {
                true => 0,
                false => 3600
            },
            'schemaCache' => match ((bool)env('YII_DEBUG')) {
                true => null,
                false => 'cache'
            },
        ],
        'log' =>  require __DIR__ . '/' . match ((bool)env('YII_DEBUG')) {
            true => 'common_logs_file.php',
            false => 'common_logs_db.php'
        },
        'settings' => [
            'class' => Settings::class,
            'preLoad' => [
                'system'
            ]
        ],
    ],
];