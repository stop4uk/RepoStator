<?php

namespace app\components\attachfiles;

use ReflectionClass;

use Yii;
use yii\base\{
    Behavior,
    Exception
};
use yii\data\ArrayDataProvider;
use yii\helpers\FileHelper;

final class AttachFileBehavior extends Behavior
{
    public $storageID;
    public $modelName;
    public $modelKey;
    public $attachRules;

    private $filesInDB = null;

    public function attach($owner)
    {
        parent::attach($owner);

        if ( !$this->storageID ) {
            $this->storageID = AttachFileHelper::STORAGE_LOCAL;
        }

        if ( !$this->modelName ) {
            $this->modelName = (new ReflectionClass($this->owner))->getShortName();
        }

        if ( !$this->modelKey ) {
            $this->modelKey = 'id';
        }

        if ( !$this->attachRules ) {
            $this->attachRules = [
                'default' => [
                    'name' => 'Без категории',
                    'tags' => 'default',
                    'rules' => [
                        ['file']
                    ]
                ]
            ];
        }
    }

    public function attachFile(
        string $inputFile,
        string $type,
        string|null $path = null,
        string|null $name = null,
        string|null $extension = null,
        string|null $mime = null,
        string|int|null $size = null
    ): bool {
        $fileData = $this->parseFileData($inputFile, $name, $extension, $mime, $size);
        $key = (string)$this->owner->{$this->modelKey};

        $pathToSave = $path ?: ($this->modelName . DIRECTORY_SEPARATOR . $key);
        $fileName = implode('.', [$fileData['nameSave'], $fileData['extension']]);
        $saveFile = AttachFileHelper::saveToStorage($this->storageID, $inputFile, $pathToSave, $fileName);

        $lastAttachedFile = AttachFileModel::find()->lastVersion($this->modelName, $key, $type)->one();
        $file_version = $lastAttachedFile ? ($lastAttachedFile->file_version+1) : 1;

        if ($saveFile) {
            try {
                unlink($inputFile);
            } catch (\Exception $e) {};

            $model = new AttachFileModel([
                'storage' => $this->storageID,
                'name' => $fileData['nameOrig'],
                'modelName' => $this->modelName,
                'modelKey' => $key,
                'file_type' => $type,
                'file_hash' => $fileData['nameSave'],
                'file_path' => $pathToSave,
                'file_size' => $fileData['size'],
                'file_extension' => $fileData['extension'],
                'file_mime' => $fileData['mime'],
                'file_tags' => $this->attachRules[$type]['tags'] ?? null,
                'file_version' => $file_version,
                'file_status' => AttachFileHelper::FSTATUS_ACTIVE,
                'customer_id' => Yii::$app->getUser()->getIdentity()->organization_id ?: null
            ]);

            if ($model->save()) {
                return true;
            }

            //Если, запись в БД не получилась, удаляем записанный файл
            AttachFileHelper::removeFromStorageByPath($this->storageID, $pathToSave . DIRECTORY_SEPARATOR . $fileName);
        }

        return false;
    }

    public function detachFiles(
        string|null $hash = null,
        string|null $type = null,
        string|array|null $tags = null,
        string|null $tagsCondition = null
    ): bool {
        $attachedFiles = AttachFileModel::find()
            ->byModel($this->modelName)
            ->byHash($hash)
            ->byType($type)
            ->byTags($tags, $tagsCondition)->all();

        if (!$attachedFiles) {
            return true;
        }

        $filesToDeleteFromStorage = [];
        $completeDelete = true;
        foreach ($attachedFiles as $attach) {
            $arrayFilesItem = [
                'storageID' => $attach->storage,
                'pathToFile' => $attach->file_path . DIRECTORY_SEPARATOR . implode('.', [$attach->file_hash, $attach->file_extension])
            ];

            try {
                if ($attach->delete()) {
                    $filesToDeleteFromStorage[] = $arrayFilesItem;
                }
            } catch(Exception $e) { $completeDelete = false; }
        }

        foreach ($filesToDeleteFromStorage as $file) {
            AttachFileHelper::removeFromStorageByPath($file['storageID'], $file['pathToFile']);
        }

        return $completeDelete;
    }

    public function getAttachFile(string $hash): array|null
    {
        $file = AttachFileModel::find()
            ->byModel($this->modelName)
            ->byHash($hash)
            ->one();

        if ($file) {
            $pathToFile = $file->file_path . DIRECTORY_SEPARATOR . implode('.', [$file->file_hash, $file->file_extension]);
            return [
                'content' => AttachFileHelper::readFromStorage($file->storage, $pathToFile),
                'name' => $file->name
            ];
        }

        return null;
    }

    public function getAttachedFiles(bool $useDataProvider = true): ArrayDataProvider|array
    {
        $files = $this->getFilesInDB();

        return match($useDataProvider) {
            true => new ArrayDataProvider([
                'allModels' => $files,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]),
            false => $files
        };
    }

    public function getAttachedFilesByType(
        string $type,
        bool $useDataProvider = false
    ): array {
        $files = $this->getFilesInDB($type);

        return match($useDataProvider) {
            true => new ArrayDataProvider([
                'allModels' => $files,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]),
            false => $files
        };
    }

    public function getAttachedFileTypeName(string $type): string|null
    {
        $types = [];
        foreach ($this->attachRules as $ruleType => $data) {
            $types[$ruleType] = $data['name'];
        }

        return $types[$type] ?? null;
    }

    public function getCanFilesToAttach(): array
    {
        $files = $this->getFilesInDB();
        $countsByType = [];

        if ( $files ) {
            $result = [];
            foreach ($files as $file) {
                $countsByType[$file->file_type] = isset($countsByType[$file->file_type]) ? $countsByType[$file->file_type]+1 : 1;
            }

            foreach($this->attachRules as $file_type => $file_params){
                if (
                    !isset($file_params['maxFiles'])
                    || !isset($countsByType[$file_type])
                    || (
                        isset($countsByType[$file_type])
                        && $countsByType[$file_type] < $file_params['maxFiles']
                    )
                ) {
                    $result[$file_type] = $file_params;
                }
            }

            return $result;
        }

        return $this->attachRules;
    }

    public function getOnePhotoFile(): string|null
    {
        $files = $this->getFilesInDB();
        if (!$files) {
            return null;
        }

        /** @var AttachFileModel $model */
        $model = $files[0];
        $filePath = $model->file_path . DIRECTORY_SEPARATOR . implode('.', [$model->file_hash, $model->file_extension]);
        $photo = base64_encode(AttachFileHelper::readFromStorage($model->storage, $filePath));
        return "data:image/{$model->file_extension};base64,$photo";
    }

    private function getFilesInDB(string|null $type = null)
    {
        if ( $this->filesInDB === null ) {
            $this->filesInDB = AttachFileModel::find()
                ->byModel($this->modelName)
                ->byKey($this->owner->{$this->modelKey})
                ->byStatus(AttachFileHelper::FSTATUS_ACTIVE)
                ->byType($type)
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        }

        return $this->filesInDB;
    }

    private function parseFileData(
        string $path,
        string|null $name = null,
        string|null $extension = null,
        string|null $mime = null,
        string|int|null $size = null
    ): array {
        $fileInfo = pathinfo($path);

        return [
            'nameOrig' => $name ?: $fileInfo['basename'],
            'nameSave' => Yii::$app->getSecurity()->generateRandomString(),
            'extension' => $extension ?: $fileInfo['extension'],
            'size' => $size ?: filesize($path),
            'mime' =>$mime ?: FileHelper::getMimeType($path)

        ];
    }
}
