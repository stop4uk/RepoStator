<?php

use app\components\base\BaseAR;

return [
    [
        'id' => 1,
        'report_id' => 1,
        'name' => 'Структура 1',
        'content' => '{"groups":[""],"constants":[["record1","record2","record3","record4","record6"]]}',
        'use_union_rules' => 1,
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 2,
        'report_id' => 2,
        'name' => 'Структура 2',
        'content' => '{"groups":[""],"constants":[["record1","record2","record3","record4","record5","record6"]]}',
        'use_union_rules' => 1,
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 3,
        'report_id' => 3,
        'name' => 'Структура 3',
        'content' => '{"groups":[""],"constants":[["record1","record2","record3"]]}',
        'use_union_rules' => 0,
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
        'record_status' => BaseAR::RSTATUS_DELETED
    ],
];