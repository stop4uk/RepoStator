<?php

use app\components\base\BaseAR;

return [
    [
        'id' => 1,
        'record' => 'constantRuleTest',
        'name' => 'КонстантПравило',
        'rule' => '"record1"',
        'created_uid' => 1,
        'created_gid' => 1,
        'created_at' => time()
    ],
    [
        'id' => 2,
        'record' => 'constantRuleTest1',
        'name' => 'КонстантПравило1',
        'rule' => '"record1"',
        'created_uid' => 1,
        'created_gid' => 1,
        'created_at' => time(),
        'record_status' => BaseAR::RSTATUS_DELETED
    ]
];