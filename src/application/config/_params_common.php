<?php

use app\components\attachedFiles\AttachFileHelper;

return [
    'bsVersion' => 5.1,
    'storageToUploadReportTemplates' => AttachFileHelper::STORAGE_LOCAL,
    'storageToSaveReportFiles' => AttachFileHelper::STORAGE_LOCAL,
];