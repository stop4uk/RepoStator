<?php

use yii\db\Connection;
use yii\web\{
    UrlNormalizer,
    Cookie
};
use Symfony\Component\Mailer;

use creocoder\flysystem\LocalFilesystem;
use app\components\attachedFiles\AttachFileHelper;
use app\modules\users\components\{
    Identity,
    rbac\PhpDBRbacManager
};

return [
    'id' => 'repostator-test',
    'basePath' => dirname(__DIR__) . '/../',
    'aliases' => [
        '@root' => dirname(__DIR__, 2),
    ],
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
];