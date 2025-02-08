<?php

use app\components\attachedFiles\AttachFileHelper;
use app\modules\reports\entities\ReportFormJobEntity;

return [
    [
        'id' => 1,
        'job_id' => Yii::$app->getSecurity()->generateRandomString(6),
        'job_status' => ReportFormJobEntity::STATUS_COMPLETE,
        'report_id' => 1,
        'template_id' => 1,
        'form_period' => '01.01.2025 - ' . date('d.m.Y', time()),
        'storage' => AttachFileHelper::STORAGE_LOCAL,
        'file_name' => 'download_test',
        'file_hash' => Yii::$app->getSecurity()->generateRandomString(12),
        'file_path' => 'downloads/',
        'file_size' => 1552,
        'file_extension' => "xlsx",
        'file_mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'created_at' => time(),
        'created_uid' => 1,
        'created_gid' => 1,
        'updated_at' => time(),
    ]
];