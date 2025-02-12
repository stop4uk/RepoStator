<?php

use app\components\base\BaseAR;

return [
    [
        'id' => 1,
        'user_id' => 1,
        'group_id' => 1,
        'created_at' => time(),
        'created_uid' => 1,
    ],
    [
        'id' => 2,
        'user_id' => 2,
        'group_id' => 2,
        'created_at' => time(),
        'created_uid' => 1,
    ],
    [
        'id' => 3,
        'user_id' => 3,
        'group_id' => 3,
        'created_at' => time(),
        'created_uid' => 1,
    ],
    [
        'id' => 4,
        'user_id' => 4,
        'group_id' => 2,
        'created_at' => time(),
        'created_uid' => 1,
        'record_status' => BaseAR::RSTATUS_DELETED
    ],
];