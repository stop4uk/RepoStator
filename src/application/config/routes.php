<?php

use yii\web\UrlNormalizer;

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'normalizer' => [
        'class' => UrlNormalizer::class,
        'normalizeTrailingSlash' => true,
        'collapseSlashes' => true,
    ],
    'rules' => [
        'download' => 'dashboard/download',

        #Авторизация, регистрация, восстановление и подтверждение учетных записей
        '<action:(login|logout|register)>' => 'auth/default/<action>',
        'recovery' => 'auth/recovery/index',
        'recovery/<action>' => 'auth/recovery/<action>',
        'verification' => 'auth/verification/index',
        'verification/<action>' => 'auth/verification/<action>',

        #Отчеты
        'reports' => 'reports/default',
        'reports/<action:(create|view|edit|delete)>' => 'reports/default/<action>',

        #Группы
        'admin/groups' => 'admin/groups/default',
        'admin/groups/<action:(create|view|edit|delete)>' => 'admin/groups/default/<action>',

        #Очереди
        'admin/queue' => 'admin/queue/default',
    ],
];