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
use kartik\select2\Select2Asset;
use klisl\nestable\NestableAsset;

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
            'path' => '@root/_storage'
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
            'defaultDuration' => (int)env('YII_DURATION_CACHE', 3600)
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
                'scheme' => env('MAIL_SCHEME', 'smtp'),
                'host' => env('MAIL_HOST', 'mailhog'),
                'username' => env('MAIL_USERNAME', ''),
                'password' => env('MAIL_PASSWORD', ''),
                'port' => (int)env('MAIL_PORT', 1025),
            ],
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=' . env('DB_HOST', 'mysql') . ';port=' . env('DB_PORT', '3306') . ';dbname=' . env('DB_NAME', 'repostator'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS', ''),
            'charset' => 'utf8',
            'enableSchemaCache' => !(bool)env('YII_DEBUG', true),
            'schemaCacheDuration' => match ((bool)env('YII_DEBUG', true)) {
                true => 0,
                false => (int)env('YII_DURATION_CACHE', 3600)
            },
            'schemaCache' => match ((bool)env('YII_DEBUG', true)) {
                true => null,
                false => 'cache'
            },
        ],
        'log' =>  require __DIR__ . '/' . match ((bool)env('YII_DEBUG', true)) {
            true => 'common_logs_file.php',
            false => 'common_logs_db.php'
        },
        'settings' => [
            'class' => Settings::class,
            'preLoad' => [
                'system'
            ]
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => (bool)env('YII_DEBUG', false),
            'forceCopy' => (bool)env('YII_DEBUG', false),
            'basePath' => '@assets',
            'bundles' => [
                Select2Asset::class => [
                    'sourcePath' => '@resources',
                    'css' => ['assets/components/select2/css/select2.css'],
                    'js' => ['assets/components/select2/js/select2.full.js'],
                ],
                NestableAsset::class => [
                    'sourcePath' => '@resources',
                    'css' => ['assets/components/nestable/css/nestable.css'],
                    'js' => ['assets/components/nestable/js/jquery.nestable.js'],
                ]
            ],
        ],
    ],
];