<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=repostator',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => false,
        ]
    ]
];
