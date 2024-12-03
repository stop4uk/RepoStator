<?php

namespace app\components\attachfiles;

use Yii;
use yii\base\Exception;

final class AttachFileHelper
{
    const STORAGE_LOCAL = 'LStorage';
    const STORAGE_YCLOUD = 'YCLoud';
    const STORAGES = [
        self::STORAGE_LOCAL => 'Локальное хранилище',
        self::STORAGE_YCLOUD => 'Яндекс S3'
    ];

    const FSTATUS_ARCHIVE = 0;
    const FSTATUS_ACTIVE = 1;
    const FSTATUS_UPDATED = 2;
    const FSTATUSES = [
        self::FSTATUS_ARCHIVE => 'Архивный',
        self::FSTATUS_ACTIVE => 'Текущий',
        self::FSTATUS_UPDATED => 'Был обновлен',
    ];

    public static function saveToStorage(
        string $storageID,
        string $pathInput,
        string $pathToSave,
        string $fileName): bool
    {
        $storage = Yii::$app->get($storageID);
        $storage->createDir($pathToSave);

        $stream = fopen($pathInput, 'r+');
        return $storage->writeStream($pathToSave . DIRECTORY_SEPARATOR . $fileName, ($stream ?: file_get_contents($pathInput)));
    }

    public static function readFromStorage(
        string $storageID,
        string $pathToFile
    ): string|null {
        $storage = Yii::$app->get($storageID);
        $readFile = $storage->read($pathToFile);

        return $readFile ?: null;
    }

    public static function readStreamFromStorage(
        string $storageID,
        string $pathToFile
    ): string|null {
        $storage = Yii::$app->get($storageID);
        $readFile = $storage->readStream($pathToFile);
        $contents = stream_get_contents($readFile);
        fclose($readFile);

        return $contents ?: null;
    }

    public static function removeFromStorageByPath(
        string $storageID,
        string $path
    ): bool {
        try {
            $storage = Yii::$app->get($storageID);
            return match($storage->has($path)) {
                true => $storage->delete($path),
                false => true
            };
        } catch (Exception $e) {
            return false;
        }
    }

    public static function moveToNewPath(
        string $storageID,
        string $filePath,
        string $toPath,
        string $fileName,
    ): bool
    {
        $storage = Yii::$app->get($storageID);
        $storage->createDir($toPath);
        $writeFile = $storage->writeStream($toPath . DIRECTORY_SEPARATOR . $fileName, fopen($storage->read($filePath), 'r+'));

        if ($writeFile){
            $storage->delete($filePath);
            return true;
        }

        return false;
    }

    public static function moveToAnotherStorage(
        string $fromStorageID,
        string $filePath,
        string $toStorageID,
        string $toPath,
        string $fileName
    ): bool
    {
        $storage = Yii::$app->get($fromStorageID);
        $newStorage = Yii::$app->get($toStorageID);
        $newStorage->createDir($toPath);
        $writeFile = $newStorage->writeStream($toPath . DIRECTORY_SEPARATOR . $fileName, fopen($storage->read($filePath), 'r+'));

        if ($writeFile) {
            $storage->delete($filePath);
            return true;
        }

        return false;
    }

    public static function moveToAnotherStorageByHash(
        string $hash,
        string $toStorageID,
        string $toPath,
    ): bool {
        $model = AttachFileModel::find()->hash($hash)->one();
        if ($model) {
            $fileName = implode('.', [$model->file_hash, $model->file_extension]);
            $filePath = $model->file_path . DIRECTORY_SEPARATOR . $fileName;

            return self::moveToAnotherStorage(
                fromStorageID: $model->storage,
                filePath: $filePath,
                toStorageID: $toStorageID,
                toPath: $toPath,
                fileName: $fileName
            );
        }

        return false;
    }
}
