<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\components\attachedFiles\AttachFileHelper;

/**
 * @var app\components\attachedFiles\AttachFileEntity $model
 * @var bool $canDeleted
 * @var bool $showFileAsImage
 * @var string $modelClass
 * @var string|int $modelKey
 * @var bool $fromSession Если, файлы взяты из кеша и еще не прикреплены к БД
 */

$actionParams = [
    'modelClass' => $modelClass,
    'modelKey' => $modelKey,
    'hash' => $model->file_hash ?? $model['name']
];

$urlParams = base64_encode(serialize($actionParams));

if ($showFileAsImage) {
    $filePath = $model['fullPath']
        ?? $model->file_path . DIRECTORY_SEPARATOR . implode('.', [$model->file_hash, $model->file_extension]);

    $photo = base64_encode(AttachFileHelper::readFromStorage($model->storage, $filePath));
    echo Html::img("data:image/{$model->file_extension};base64,$photo", ['class' => 'w-auto rounded']);
}

?>

<div class="row mt-2">
    <div class="col-3">
        <?= Html::a('Скачать', Url::to(['getfile', 'params' => $urlParams]), ['class' => 'btn btn-dark w-100']) ?>
    </div>
    <div class="col-9">
        <?php
            if ($canDeleted || $fromSession) {
                echo Html::tag(
                    'span',
                    Yii::t('system', 'Удалить файл'),
                    [
                        'class' => 'btn btn-danger w-100 pjax-delete-link',
                        'delete-url' => Url::to(['detachfile', 'params' => $urlParams]),
                        'pjax-container' => 'attachedFileList',
                        'role' => 'button'
                    ]
                );
            }
        ?>
    </div>
</div>
