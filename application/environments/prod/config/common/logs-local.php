<?php

use yii\log\DbTarget;

return [
    'components' => [
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                [
                    'class' => DbTarget::class,
                    'levels' => ['error'],
                    'logVars' => []
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Queue'],
                    'levels' => ['error'],
                    'logVars' => [],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Auth.Signin'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Auth.Signup'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Auth.Verification'],
                    'levels' => ['error'],
                    'logVars' => ['_GET'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Auth.Recovery'],
                    'levels' => ['error'],
                    'logVars' => ['_GET'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Users.Profile'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Users.Rights'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Users.Groups'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Users.InitialData'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Users.Admin'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.List'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.Constant'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.ConstantRule'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.Structure'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.Template'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.Send'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.Control'],
                    'levels' => ['error'],
                    'logVars' => ['_GET', '_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Reports.Jobs'],
                    'levels' => ['error'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Groups'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => DbTarget::class,
                    'categories' => ['Groups.Type'],
                    'levels' => ['error'],
                    'logVars' => ['_POST'],
                ],
            ],
        ]
    ]
];