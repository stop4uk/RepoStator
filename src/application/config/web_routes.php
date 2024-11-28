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
        'admin_groups' => [
            'class' => DefaultAdminGroupController::class,
            'viewPath' => '@resources/views/admin/groups/default'
        ],
        'admin_groups_type' => [
            'class' => TypeController::class,
            'viewPath' => '@resources/views/admin/groups/type'
        ],
        'admin_queue' => [
            'class' => DefaultAdminQueueController::class,
            'viewPath' => '@resources/views/admin/queue/default'
        ],
        'admin_queue_template' => [
            'class' => TemplateAdminQueueController::class,
            'viewPath' => '@resources/views/admin/queue/template'
        ],
        'admin_logs' => [
            'class' => LogsController::class,
            'viewPath' => '@resources/views/admin/logs',
        ],
        'admin_settings' => [
            'class' => SettingsController::class,
            'viewPath' => '@resources/views/admin/settings'
        ],
        'admin_users' => [
            'class' => UsersController::class,
            'viewPath' => '@resources/views/admin/users'
        ],
        'reports' => [
            'class' => DefaultReportController::class,
            'viewPath' => '@resources/views/reports/default'
        ],
        'reports_constant' => [
            'class' => ConstantController::class,
            'viewPath' => '@resources/views/reports/constant'
        ],
        'reports_constantrule' => [
            'class' => ConstantruleController::class,
            'viewPath' => '@resources/views/reports/constantrule'
        ],
        'reports_structure' => [
            'class' => StructureController::class,
            'viewPath' => '@resources/views/reports/structure'
        ],
        'reports_template' => [
            'class' => TemplateReportController::class,
            'viewPath' => '@resources/views/reports/template'
        ],
        'send' => [
            'class' => SendController::class,
            'viewPath' => '@resources/views/reports/send'
        ],
        'control' => [
            'class' => ControlController::class,
            'viewPath' => '@resources/views/reports/control'
        ],
        'statistic' => StatisticController::class,
        'auth_default' => [
            'class' => DefaultAuthController::class,
            'layout' => 'clear',
            'viewPath' => '@resources/views/auth/default'
        ],
        'auth_recovery' => [
            'class' => RecoveryController::class,
            'layout' => 'clear',
            'viewPath' => '@resources/views/auth/recovery'
        ],
        'auth_verification' => [
            'class' => VerificationController::class,
            'layout' => 'clear',
            'viewPath' => '@resources/views/auth/verification'
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
                '<action:(login|logout|register)>'  => 'auth_default/<action>',
                'recovery'                          => 'auth_recovery',
                'recovery/<action>'                 => 'auth_recovery/<action>',
                'verification'                      => 'auth_verification',
                'verification/<action>'             => 'auth_verification/<action>',

                #Отчеты
                'reports/constant'                  => 'reports_constant',
                'reports/constant/<action:\w+>'     => 'reports_constant/<action>',
                'reports/constantrule'              => 'reports_constantrule',
                'reports/constantrule/<action:\w+>' => 'reports_constantrule/<action>',
                'reports/structure'                 => 'reports_structure',
                'reports/structure/<action:\w+>'    => 'reports_structure/<action>',
                'reports/template'                  => 'reports_template',
                'reports/template/<action:\w+>'     => 'reports_template/<action>',

                #Админка
                'admin/groups'                                                              => 'admin_groups',
                'admin/groups/<action:(create|view|edit|delete|enable|nodeMove)>'           => 'admin_groups/<action>',
                'admin/groups/type'                 => 'admin_groups_type',
                'admin/groups/type/<action:\w+>'    => 'admin_groups_type/<action>',
                'admin/queue'                       => 'admin_queue',
                'admin/queue/template'              => 'admin_queue_template',
                'admin/logs'                        => 'admin_logs',
                'admin/logs/<action:\w+>'           => 'admin_logs/<action>',
                'admin/settings'                    => 'admin_settings',
                'admin/users'                       => 'admin_users',
                'admin/users/<action:\w+>'          => 'admin_users/<action>',
            ],
        ]
    ]
];