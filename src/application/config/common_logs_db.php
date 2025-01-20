<?php

use yii\log\DbTarget;
use app\useCases\system\entities\LogEntity;

return [
    'traceLevel' => 3,
    'targets' => [
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'levels' => ['error'],
            'logVars' => []
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Queue'],
            'levels' => ['error'],
            'logVars' => [],
        ],

        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Auth.Signin'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Auth.Signup'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Auth.Verification'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_GET'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Auth.Recovery'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_GET'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Users.Profile'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Users.Rights'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Users.Groups'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Users.InitialData'],
            'levels' => ['error', 'warning'],
            'logVars' => ['_POST'],
        ],

        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Users.Admin'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.List'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.Constant'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.ConstantRule'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.Structure'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.Template'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.Send'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.Control'],
            'levels' => ['error'],
            'logVars' => ['_GET', '_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Reports.Jobs'],
            'levels' => ['error'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Groups'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
        [
            'class' => DbTarget::class,
            'logTable' => LogEntity::tableName(),
            'categories' => ['Groups.Type'],
            'levels' => ['error'],
            'logVars' => ['_POST'],
        ],
    ],
];