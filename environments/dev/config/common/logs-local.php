<?php

use yii\log\FileTarget;

return [
    'components' => [
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/app.log',
                    'logVars' => []
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Queue'],
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                    'logFile' => '@runtime/logs/queue.log'
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Auth.Signin'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/auth/signin.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Auth.Signup'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/auth/signup.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Auth.Verification'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET'],
                    'logFile' => '@runtime/logs/auth/verification.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Auth.Recovery'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET'],
                    'logFile' => '@runtime/logs/auth/recovery.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Users.Profile'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/profile.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Users.Rights'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/rights.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Users.Groups'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/groups.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Users.InitialData'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/initialdata.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Users.Admin'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/users/admin.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.List'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/list.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.Constant'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/constant.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.ConstantRule'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/constant_rule.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.Structure'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/structure.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.Template'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/template.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.Send'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/reports/send.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.Control'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST'],
                    'logFile' => '@runtime/logs/reports/control.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Reports.Jobs'],
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/reports/jobs.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Groups'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/groups/general.log',
                ],
                [
                    'class' => FileTarget::class,
                    'categories' => ['Groups.Type'],
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_POST'],
                    'logFile' => '@runtime/logs/groups/type.log',
                ],
            ],
        ]
    ]
];