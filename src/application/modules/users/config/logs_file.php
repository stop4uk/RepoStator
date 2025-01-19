<?php

return [
    [
        'categories' => ['Auth.Signin'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/auth/signin.log',
    ],
    [
        'categories' => ['Auth.Signup'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/auth/signup.log',
    ],
    [
        'categories' => ['Auth.Verification'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_GET'],
        'logFile' => '@runtime/logs/auth/verification.log',
    ],
    [
        'categories' => ['Auth.Recovery'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_GET'],
        'logFile' => '@runtime/logs/auth/recovery.log',
    ],
    [
        'categories' => ['Users.Profile'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/profile.log',
    ],
    [
        'categories' => ['Users.Rights'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/rights.log',
    ],
    [
        'categories' => ['Users.Groups'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/groups.log',
    ],
    [
        'categories' => ['Users.InitialData'],
        'levels' => ['error', 'warning'],
        'logVars' => ['_POST'],
        'logFile' => '@runtime/logs/users/initialdata.log',
    ],
];