<?php

use app\components\base\BaseAR;

return [
    [
        'id' => 1,
        'code' => '001',
        'name' => 'Главная группа',
        'name_full' => null,
        'description' => 'General group',
        'accept_send' => 0,
        'type_id' => null,
        'created_at' => time(),
        'created_uid' => 1,
        'updated_at' => null,
        'updated_uid' => null,
        'record_status' => BaseAR::RSTATUS_ACTIVE
    ],
    [
        'id' => 2,
        'code' => '002',
        'name' => 'Тестовая группа',
        'name_full' => 'Тестовая группа с передачей отчета',
        'description' => 'Test group',
        'accept_send' => 1,
        'type_id' => 1,
        'created_at' => time(),
        'created_uid' => 1,
        'updated_at' => null,
        'updated_uid' => null,
        'record_status' => BaseAR::RSTATUS_ACTIVE
    ],
    [
        'id' => 3,
        'code' => '',
        'name' => 'Тестовая группа 2',
        'name_full' => null,
        'description' => 'Test group 2',
        'accept_send' => 1,
        'type_id' => null,
        'created_at' => time(),
        'created_uid' => 1,
        'updated_at' => null,
        'updated_uid' => null,
        'record_status' => BaseAR::RSTATUS_ACTIVE
    ],
    [
        'id' => 4,
        'code' => 'A122',
        'name' => 'Тестовая группа 3',
        'name_full' => 'Тестовая группа 3',
        'description' => null,
        'accept_send' => 0,
        'type_id' => 1,
        'created_at' => time(),
        'created_uid' => 1,
        'updated_at' => null,
        'updated_uid' => null,
        'record_status' => BaseAR::RSTATUS_ACTIVE
    ],
];