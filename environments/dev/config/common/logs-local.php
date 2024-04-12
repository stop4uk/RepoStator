<?php

return [
    'components' => [
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/app.log',
                    'logVars' => []
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Queue'],
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                    'logFile' => '@runtime/logs/queue.log'
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Auth.Signin'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/auth/signin.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Auth.Signup'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/auth/signup.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Auth.Verification'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET'],
                    'logFile' => '@runtime/logs/auth/verification.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Auth.Recovery'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET'],
                    'logFile' => '@runtime/logs/auth/recovery.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Users.Profile'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/profile.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Users.Rights'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/rights.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Users.Groups'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/groups.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Users.InitialData'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/initialdata.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Users.Admin'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/admin.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.List'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/list.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.Constant'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/constant.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.ConstantRule'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/constant_rule.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.Structure'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/structure.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.Template'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/template.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.Send'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/send.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.Control'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST'],
                    'logFile' => '@runtime/logs/reports/control.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Reports.Jobs'],
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/reports/jobs.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Groups'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/groups/general.log',
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'categories' => ['Groups.Type'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/groups/type.log',
                ],
            ],
        ]
    ]
];