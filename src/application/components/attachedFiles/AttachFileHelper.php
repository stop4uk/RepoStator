<?php

namespace app\components\attachedFiles;

use Yii;
use yii\base\Exception;
use yii\web\Application;
use yii\helpers\ArrayHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\attachedFiles
 *
 *
 * Full information about base extension https://github.com/creocoder/yii2-flysystem
 */
final class AttachFileHelper
{
    const STORAGE_LOCAL = 'LStorage';
    const STORAGE_S3CLOUD = 'S3Cloud';

    const FSTATUS_ARCHIVE = 0;
    const FSTATUS_ACTIVE = 1;
    const FSTATUS_UPDATED = 2;

    public static function getStorageName(
        bool $asList = false,
        ?string $storageID = null
    ): string|array|null {
        $items = [
            self::STORAGE_LOCAL => Yii::t('system', 'Локальное хранилище'),
            self::STORAGE_S3CLOUD => Yii::t('system', 'Хранилище S3')
        ];

        return $asList ? $items : ArrayHelper::getValue($items, $storageID);
    }

    public static function getFileStatus(
        bool $asList = false,
        ?string $status = null
    ): string|array|null {
        $items = [
            self::FSTATUS_ARCHIVE => Yii::t('system', 'Архивный'),
            self::FSTATUS_ACTIVE => Yii::t('system', 'Текущий'),
            self::FSTATUS_UPDATED => Yii::t('system', 'Был обновлен'),
        ];

        return $asList ? $items : ArrayHelper::getValue($items, $status);
    }

    public static function saveToStorage(
        string $storageID,
        string $pathInput,
        string $pathToSave,
        string $fileName
    ): bool {
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
        if (!$storage->has($pathToFile)) {
            return null;
        }

        $readFile = $storage->readStream($pathToFile);
        $contents = stream_get_contents($readFile);
        fclose($readFile);

        return $contents ?: null;
    }

    public static function removeFromStorage(
        string $storageID,
        string $path
    ): bool {
        try {
            $storage = Yii::$app->get($storageID);
            return match ($storage->has($path)) {
                true => $storage->delete($path),
                false => true
            };
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getSessionKey(string|null $modelName = null): string
    {
        if (Yii::$app instanceof Application) {
            return implode('_', [($modelName ? str_replace('\\', '_', $modelName) : '') . 'sUpload', Yii::$app->getUser()->getId()]);
        }

        return 'sUploadConsole';
    }
}
