<?php

use yii\caching\FileCache;
use yii\queue\{
    db\Queue,
    LogBehavior
};
use yii\mutex\MysqlMutex;
use yii\symfonymailer\Mailer;

return [
    'aliases' => [
        '@migrations' => '@resources/migrations',
    ],
    'components' => [
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
                'scheme' => 'smtp',
                'host' => 'mailhog',
                'username' => '',
                'password' => '',
                'port' => '1025',
            ],
        ],
    ]
];