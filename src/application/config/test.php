<?php

use yii\db\Connection;
use yii\web\{
    UrlNormalizer,
    Cookie
};
use yii\console\controllers\FixtureController;
use yii\caching\FileCache;
use yii\i18n\PhpMessageSource;
use yii\mutex\MysqlMutex;
use yii\queue\{
    db\Queue,
    LogBehavior
};
use Symfony\Component\Mailer;
use creocoder\flysystem\LocalFilesystem;
use kartik\select2\Select2Asset;

use app\components\{
    attachedFiles\AttachFileHelper,
    bootstrap\CommonBootstrap,
    bootstrap\WebBootstrap,
    events\CommonEventHandler,
    events\WebEventHandler,
    settings\Settings
};
use app\modules\{
    users\Module as UsersModule,
    reports\Module as ReportsModule,
    admin\Module as AdminModule,
    users\components\Identity,
    users\components\rbac\PhpDBRbacManager
};

$params = array_merge(
    require __DIR__ . '/_params_common.php',
    require __DIR__ . '/_params_console.php',
);

return [
    'id' => 'repostator-test',
    'name' => env('YII_APP_NAME'),
    'sourceLanguage' => env('YII_APP_SOURCE_LANG'),
    'basePath' => dirname(__DIR__) . '/../',
    'runtimePath' => dirname(__DIR__) . '/../runtime',
    'defaultRoute' => 'reports/dashboard',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@root' => dirname(__DIR__, 2),
        '@app' => dirname(__DIR__) . '/',
        '@runtime' => '@root/runtime',
        '@resources' => '@root/resources',
        '@web' => '@root/public',
        '@assets' => '@root/public/assets',
    ],
    'viewPath' => '@resources/views',
    'bootstrap' => [
        'log',
        'queue',
        CommonBootstrap::class,
        CommonEventHandler::class,
        WebBootstrap::class,
        WebEventHandler::class,
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => FixtureController::class,
            'namespace' => 'root\tests\fixtures',
        ],
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
            'path' => '@root/_storage_test'
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
            'linkAssets' => true,
            'forceCopy' => (bool)env('YII_DEBUG', false),
            'basePath' => '@assets',
            'bundles' => [
                Select2Asset::class => [
                    'sourcePath' => '@resources',
                    'css' => ['assets/components/select2/css/select2.css'],
                    'js' => ['assets/components/select2/js/select2.full.js'],
                ],
            ],
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
        'user' => [
            'identityClass' => Identity::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['login'],
            'identityCookie' => [
                'name' => '_identity-' . env('PROJECT_NAME', 'simple'),
            ],
        ],
        'authManager' => PhpDBRbacManager::class,
        'session' => [
            'timeout' => env('YII_DURATION_SESSION'),
            'name' => env('PROJECT_NAME') . '_session',
            'useCookies'   => true,
            'cookieParams' => [
                'httponly' => true,
                'secure' => true,
                'sameSite' => Cookie::SAME_SITE_STRICT,
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/fault',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@resources'
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-' . env('PROJECT_NAME'),
            'cookieValidationKey' => env('YII_COOKIE_VALIDATION_KEY'),
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => UrlNormalizer::class,
                'normalizeTrailingSlash' => true,
                'collapseSlashes' => true,
            ],
            'rules' => [
                'profile'                                                                       => 'users/profile',
                'profile/<action:\w+>'                                                          => 'users/profile/<action>',
                'recovery'                                                                      => 'users/recovery',
                'recovery/<action:\w+>'                                                         => 'users/recovery/<action>',
                'verification'                                                                  => 'users/verification',
                'verification/<action:\w+>'                                                     => 'users/verification/<action>',
                '<action:(login|logout|register)>'                                              => 'users/auth/<action>',
                '<controller:(dashboard|send|control|statistic)>'                               => 'reports/<controller>',
                '<controller:(dashboard|send|control|statistic)>/<action>'                      => 'reports/<controller>/<action>',

                '<module:\w+>'                                                                  => '<module>/default',
                '<module:\w+>/<action:(index|create|view|edit|delete|enable)>'                  => '<module>/default/<action>',

                'reports/<controller:(constant|constantrule|structure|template)>'               => 'reports/<controller>',
                'reports/<controller:(constant|constantrule|structure|template)>/<action:\w+>'  => 'reports/<controller>/<action>',

                'admin/groups'                                                                  => 'admin/groups/default',
                'admin/groups/<action:(create|view|edit|delete|enable|map|nodeMove)>'           => 'admin/groups/default/<action>',
                'admin/queue'                                                                   => 'admin/queue/default',
            ],
        ]
    ],
    'params' => $params
];