<?php

use yii\log\{
    FileTarget,
    DbTarget
};

use app\entities\LogEntity;

$targets = [
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Signin'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/auth/signin.log',
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Signup'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/auth/signup.log',
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Verification'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_GET'],
        'logFile' => '@runtime/logs/auth/verification.log',
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Auth.Recovery'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_GET'],
        'logFile' => '@runtime/logs/auth/recovery.log',
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Profile'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/profile.log',
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Rights'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/rights.log',
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.Groups'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/groups.log',
    ],
    [
        'logTable' => LogEntity::tableName(),
        'categories' => ['Users.InitialData'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/initialdata.log',
    ],
];

return match((bool)env('YII_DEBUG')) {
    true => array_map(function($setting) {
        unset($setting['logTable']);
        return new FileTarget($setting);
    }, $targets),
    false => array_map(function($setting) {
        unset($setting['logFile']);
        return new DbTarget($setting);
    }, $targets)
};
