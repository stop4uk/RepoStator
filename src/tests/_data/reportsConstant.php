<?php

use app\components\base\BaseAR;
use app\helpers\CommonHelper;

return [
    [
        'id' => 1,
        'record' => 'record1',
        'name' => 'ТестКонстанта1',
        'name_full' => 'Тестовая константа 1',
        'crated_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 2,
        'record' => 'record2',
        'name' => 'ТестКонстанта2',
        'name_full' => 'Тестовая константа 2',
        'crated_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 3,
        'record' => 'record3',
        'name' => 'ТестКонстанта3',
        'name_full' => 'Тестовая константа 3',
        'crated_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 4,
        'record' => 'record4',
        'name' => 'ТестКонстанта4',
        'name_full' => 'Тестовая константа 4',
        'crated_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 5,
        'record' => 'record5',
        'name' => 'ТестКонстанта5',
        'name_full' => 'Тестовая константа 5',
        'reports_only' => CommonHelper::implodeField([2]),
        'crated_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 6,
        'record' => 'record6',
        'name' => 'ТестКонстанта6',
        'name_full' => 'Тестовая константа 6',
        'reports_only' => CommonHelper::implodeField([1, 2]),
        'crated_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 7,
        'record' => 'record7',
        'name' => 'ТестКонстанта7',
        'name_full' => 'Тестовая константа 7',
        'crated_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
        'record_status' => BaseAR::RSTATUS_DELETED
    ],
];