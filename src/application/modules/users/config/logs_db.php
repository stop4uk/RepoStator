<?php

use app\entities\LogEntity;

return [
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Signin'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Signup'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Verification'],
        'levels' => ['error'],
        'logVars' => ['_GET'],
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Recovery'],
        'levels' => ['error'],
        'logVars' => ['_GET'],
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Profile'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Rights'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Groups'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.InitialData'],
        'levels' => ['error'],
        'logVars' => ['_POST'],
    ]
];