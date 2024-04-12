<?php

return [
    'aliases' => [
        '@migrations' => '@resources/migrations',
    ],
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
            'as log' => \yii\queue\LogBehavior::class,
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
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