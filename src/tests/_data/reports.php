<?php

use app\components\base\BaseAR;

return [
    [
        'id' => 1,
        'name' => 'Тестовый_отчет',
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1
    ],
    [
        'id' => 2,
        'name' => 'Тестовый_отчет2',
        'description' => '"<p>Тестовое описание отчета 2</p>"',
        'left_period' => 1440,
        'block_minutes' => 30,
        'null_day' => 1,
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1
    ],
    [
        'id' => 3,
        'name' => 'Тестовый_отчет3',
        'description' => '"<p>Тестовое описание отчета 3</p>"',
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
        'record_status' => BaseAR::RSTATUS_DELETED
    ],
    [
        'id' => 4,
        'name' => 'Тестовый отчет4',
        'description' => '"<p>Тестовое описание отчета 4</p>"',
        'created_at' => time(),
        'created_uid' => 3,
        'created_gid' => 3,
    ],
];