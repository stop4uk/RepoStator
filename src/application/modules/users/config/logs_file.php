<?php

use yii\log\FileTarget;

return [
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
];