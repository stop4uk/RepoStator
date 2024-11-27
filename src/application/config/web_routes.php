<?php

use yii\web\UrlNormalizer;

use app\useCases\admin\controllers\{
    groups\DefaultController as DefaultAdminGroupController,
    groups\TypeController,
    queue\DefaultController as DefaultAdminQueueController,
    queue\TemplateController as TemplateAdminQueueController,
    LogsController,
    SettingsController,
    UsersController
};
use app\useCases\reports\controllers\{
    ConstantController,
    ConstantruleController,
    ControlController,
    DefaultController as DefaultReportController,
    SendController,
    StatisticController,
    StructureController,
    TemplateController as TemplateReportController
};
use app\useCases\system\controllers\{
    DashboardController,
    ErrorController,
    OfflineController
};
use app\useCases\users\controllers\{
    auth\DefaultController as DefaultAuthController,
    auth\RecoveryController,
    auth\VerificationController,
    ProfileController
};

return [
    'defaultRoute' => 'dashboard',
    'controllerMap' => [
        'error' => [
            'class' => ErrorController::class,
            'layout' => 'clear'
        ],
        'offline' => [
            'class' => OfflineController::class,
            'layout' => 'clear',
        ],
        'dashboard' => DashboardController::class,
        'admin\groups' => DefaultAdminGroupController::class,
        'admin\groups\type' => TypeController::class,
        'admin\queue' => DefaultAdminQueueController::class,
        'admin\queue\template' => TemplateAdminQueueController::class,
        'admin\logs' => LogsController::class,
        'admin\settings' => SettingsController::class,
        'admin\users' => UsersController::class,
        'reports' => DefaultReportController::class,
        'reports\constant' => ConstantController::class,
        'reports\constantrule' => ConstantruleController::class,
        'reports\structure' => StructureController::class,
        'reports\control' => ControlController::class,
        'reports\template' => TemplateReportController::class,
        'send' => SendController::class,
        'statistic' => StatisticController::class,
        'auth\default' => [
            'class' => DefaultAuthController::class,
            'layout' => 'clear'
        ],
        'auth\recovery' => [
            'class' => RecoveryController::class,
            'layout' => 'clear'
        ],
        'auth\verification' => [
            'class' => VerificationController::class,
            'layout' => 'clear'
        ],
        'profile' => ProfileController::class

    ],
    'components' => [
        'errorHandler' => [
            'errorAction' => 'error/fault',
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
        ]
    ]
];