<?php

use yii\log\DbTarget;
use app\entities\LogEntity;

return [
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Signin'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Signup'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Verification'],
        'levels' => ['error'],
        'logVars' => ['_GET'],
    ],
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Recovery'],
        'levels' => ['error'],
        'logVars' => ['_GET'],
    ],
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Profile'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Rights'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Groups'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'class' => DbTarget::class,
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.InitialData'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ]
];