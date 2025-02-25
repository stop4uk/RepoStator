<?php

use app\helpers\CommonHelper;
use app\modules\reports\entities\ReportFormTemplateEntity;

return [
    [
        'id' => 1,
        'report_id' => 1,
        'name' => 'Шаблон 1',
        'form_datetime' => ReportFormTemplateEntity::REPORT_DATETIME_PERIOD,
        'form_type' => ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE,
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
        'form_datetime' => ReportFormTemplateEntity::REPORT_DATETIME_PERIOD,
        'form_type' => ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC,
        'form_usejobs' => 0,
        'use_appg' => 0,
        'table_type' => 0,
        'table_columns' => CommonHelper::implodeField(['record1', 'record2', 'record5']),
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
    ],
    [
        'id' => 3,
        'report_id' => 2,
        'name' => 'Шаблон 3',
        'form_datetime' => ReportFormTemplateEntity::REPORT_DATETIME_PERIOD,
        'form_type' => ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC,
        'form_usejobs' => 0,
        'use_appg' => 0,
        'table_type' => 0,
        'table_columns' => CommonHelper::implodeField(['record1', 'record2', 'record5']),
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
        'record_status' => ReportFormTemplateEntity::RSTATUS_DELETED
    ],
];