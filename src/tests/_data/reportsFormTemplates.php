<?php

use app\helpers\CommonHelper;

return [
    [
        'id' => 1,
        'report_id' => 1,
        'name' => 'Шаблон 1',
        'form_datetime' => 2,
        'form_type' => 1,
        'form_usejobs' => 1,
        'use_appg' => 0,
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 2,
        'report_id' => 2,
        'name' => 'Шаблон 2',
        'form_datetime' => 2,
        'form_type' => 0,
        'form_usejobs' => 0,
        'use_appg' => 0,
        'table_type' => 0,
        'table_columns' => CommonHelper::implodeField(['record1', 'record2', 'record5']),
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
];