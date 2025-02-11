<?php

use app\modules\users\components\rbac\items\Roles;

return [
    [
        'item_name' => Roles::ADMIN,
        'user_id' => 1,
        'created_at' => time(),
        'created_uid' => null
    ],
    [
        'item_name' => Roles::ROLE_DATASEND,
        'user_id' => 2,
        'created_at' => time(),
        'created_uid' => null
    ],
    [
        'item_name' => Roles::ROLE_REPORTADD,
        'user_id' => 3,
        'created_at' => time(),
        'created_uid' => null
    ],
    [
        'item_name' => Roles::ROLE_TEMPALTEADD,
        'user_id' => 3,
        'created_at' => time(),
        'created_uid' => null
    ],
    [
        'item_name' => Roles::ROLE_DATAMAIN,
        'user_id' => 4,
        'created_at' => time(),
        'created_uid' => null
    ],
];