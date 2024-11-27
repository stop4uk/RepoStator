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

use app\components\{
    bootstrap\CommonBootstrap,
    events\handlers\CommonEventHandler,
    settings\Settings
};

return [
    'id' => getenv('PROJECT_NAME'),
    'name' => getenv('YII_APP_NAME'),
    'sourceLanguage' => getenv('YII_APP_SOURCE_LANG'),
    'basePath' => dirname(__DIR__) . '/../',
    'runtimePath' => dirname(__DIR__) . '/../runtime',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@root' => dirname(__DIR__) . '/../',
        '@app' => dirname(__DIR__) . '/',
        '@runtime' => '@root/runtime',
        '@resources' => '@root/resources',
        '@downloads' => '@runtime/files',
        '@uploads' => '@root/' . getenv('YII_UPLOADS_PATH_LOCAL'),
        '@templates' => '@uploads/' . getenv('YII_UPLOADS_PATH_LOCAL_TEMPLATE'),
    ],
    'bootstrap' => [
        'log',
        'queue',
        CommonBootstrap::class,
        CommonEventHandler::class,
    ],
    'components' => [
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
                'scheme' => getenv('MAIL_SCHEME'),
                'host' => getenv('MAIL_HOST'),
                'username' => getenv('MAIL_USERNAME'),
                'password' => getenv('MAIL_PASSWORD'),
                'port' => getenv('MAIL_PORT'),
            ],
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=' . env('DB_HOST') . ';port=' . env('DB_PORT') . ';dbname=' . env('DB_NAME'),
            'username' => env('DB_USER'),
            'password' => env('DB_PASS'),
            'charset' => 'utf8',
            'enableSchemaCache' => (bool)env('YII_DEBUG'),
            'schemaCacheDuration' => match((bool)env('YII_DEBUG')) {
                true => 0,
                false => 3600
            },
            'schemaCache' => match((bool)env('YII_DEBUG')) {
                true => null,
                false => 'cache'
            },
        ],
        'log' =>  require __DIR__ . '/common_logs.php',
        'settings' => [
            'class' => Settings::class,
            'preLoad' => [
                'system'
            ]
        ],
    ],
];