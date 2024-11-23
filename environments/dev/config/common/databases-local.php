<?php

use yii\db\Connection;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=repostator',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => false,
        ]
    ]
];
