<?php

use yii\helpers\Url;
use yii\bootstrap\Html;

use common\attachfiles\AttachFileHelper;

/**
 * @var \common\attachfiles\AttachFileModel $model
 * @var bool $canDeleted
 * @var string $modelClass
 * @var string|int $modelKey
 */

$filePath = $model->file_path . DIRECTORY_SEPARATOR . implode('.', [$model->file_hash, $model->file_extension]);
$photo = base64_encode(AttachFileHelper::readFromStorage($model->storage, $filePath));
echo Html::img("data:image/{$model->file_extension};base64,$photo", ['class' => 'w-auto rounded']);

if ($canDeleted) {
    $actionParams = [
        'modelClass' => $modelClass,
        'modelKey' => $modelKey,
        'hash' => $model->file_hash
    ];

    echo Html::tag(
        'span',
        'Удалить фотографию',
        [
            'class' => 'btn btn-danger w-75 mt-4 pjax-delete-link',
            'delete-url' => Url::to(['detachfile', 'params' => base64_encode(serialize($actionParams))]),
            'pjax-container' => 'attachedFileList',
            'role' => 'button'
        ]
    );
}