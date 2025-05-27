<?php

namespace app\components\attachedFiles;

use ReflectionClass;

use Yii;
use yii\base\{
    Behavior,
    ErrorException
};
use yii\web\Application;
use yii\data\ArrayDataProvider;
use yii\helpers\{
    FileHelper,
    Json
};

/**
 * ```php
 *  $params = [
 *       'name' => "simple",
 *       'tags' => ['default', 'tag1'],
 *       'rules' => [
 *           ['file', 'extensions' => ['xls', 'xlsx', 'ods', 'txt']]
 *       ],
 *       'maxFiles' => 2
 *  ]
 *  ```
 * @var array $params
 *
 * name - Название категории (типа загружаемого файла). string
 * tags - Теги для БД. string
 * rules - Yii2 rules для валидации. При загрузке файла применяются. array
 * maxFiles - Количество файлов в БД. Если, указано, то при загрузке, будет считаться количество активных файлов
 * с типом name и статусом: Активен. integer
 *
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\attachedFiles
 */
final class AttachFileBehavior extends Behavior
{
    public $storageID;
    public $modelName;
    public $modelKey;
    public $attachRules;

    private $filesInDB = null;
    private $_session;

    public function init(): void
    {
        if (Yii::$app instanceof Application) {
            $this->_session = Yii::$app->session;
        }

        parent::init();
    }

    public function attach($owner): void
    {
        parent::attach($owner);

        if (!$this->storageID) {
            $this->storageID = AttachFileHelper::STORAGE_LOCAL;
        }

        if (!$this->modelName) {
            $this->modelName = (new ReflectionClass($this->owner))->getShortName();
        }

        if (!$this->modelKey) {
            $this->modelKey = 'id';
        }

        if (!$this->attachRules) {
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
        string|int|null $size = null,
        bool $unlinkFile = true,
        array $additionalParams = []
    ): bool {
        $fileData = $this->parseFileData($inputFile, $name, $extension, $mime, $size);
        $key = (string)$this->owner->{$this->modelKey};

        $pathToSave = $path ?: (env('YII_UPLOADS_PATH_LOCAL', 'uploads') . DIRECTORY_SEPARATOR . $this->modelName . DIRECTORY_SEPARATOR . $key);
        $fileName = implode('.', [$fileData['nameSave'], $fileData['extension']]);
        $saveFile = AttachFileHelper::saveToStorage($this->storageID, $inputFile, $pathToSave, $fileName);

        $lastAttachedFile = AttachFileEntity::find()->lastVersion($this->modelName, $key, $type)->one();
        $file_version = $lastAttachedFile ? ($lastAttachedFile->file_version+1) : 1;

        if ($saveFile) {
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
            ]);

            if ($additionalParams) {
                foreach ($additionalParams as $key => $value) {
                    if ($model->hasAttribute($key)) {
                        $model->{$key} = $value;
                    }
                }
            }

            if ($model->save()) {
                if ($unlinkFile) {
                    try {unlink($inputFile);} catch (ErrorException $e) {};
                }

                return true;
            }

            //Если, запись в БД не получилась, удаляем записанный файл и пишем лог ошибок
            Yii::error('SaveAttachedFileToBDError' . Json::encode($model->getErrors()));
            AttachFileHelper::removeFromStorage($this->storageID, $pathToSave . DIRECTORY_SEPARATOR . $fileName);
        }

        return false;
    }

    public function attachFileFromSession(array $additionalParams = []): void
    {
        $sessionKey = AttachFileHelper::getSessionKey($this->owner::class);
        $sessionFiles = $this->_session->get($sessionKey);
        $filesToUnlink = [];

        if ($sessionFiles) {
            foreach ($sessionFiles as $file) {
                $saveFile = $this->attachFile(
                    inputFile: $file['file_path'],
                    type: $file['file_type'],
                    name: $file['name'],
                    extension: $file['file_extension'],
                    mime: $file['file_mime'],
                    unlinkFile: false,
                    additionalParams: $additionalParams
                );

                if ($saveFile) {
                    unset($sessionFiles[$file['file_hash']]);
                    $filesToUnlink[] = $file['file_path'];
                }
            }

            if ($filesToUnlink) {
                foreach ($sessionFiles as $sessionFile) {
                    try{
                        unlink($sessionFile['fullPath']);
                    } catch (ErrorException $e){}
                }
            }

            $this->_session->set($sessionKey, $sessionFiles);
        }
    }

    public function detachFiles(
        string|null $hash = null,
        string|null $type = null,
        string|array|null $tags = null,
        string|null $tagsCondition = null
    ): bool {
        $attachedFiles = AttachFileEntity::find()
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
            AttachFileHelper::removeFromStorage($file['storageID'], $file['pathToFile']);
        }

        return $completeDelete;
    }

    public function getAttachFile(string $hash): array|null
    {
        $file = AttachFileEntity::find()
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
        $files = $this->getFilesFromAllSource();

        return match ($useDataProvider) {
            true => new ArrayDataProvider([
                'allModels' => $files,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]),
            false => $files instanceof AttachFileEntity ? $files->toArray() : $files
        };
    }

    public function getAttachedFilesByType(
        string $type,
        bool $useDataProvider = false
    ): array {
        $files = $this->getFilesInDB($type);

        return match ($useDataProvider) {
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
        $files = $this->getFilesInDB()
            ?: Yii::$app->getSession()->get(AttachFileHelper::getSessionKey($this->owner::class));

        if ($files) {
            $countsByType = [];
            $result = [];

            foreach ($files as $file) {
                $countsByType[$file['file_type']] = isset($countsByType[$file['file_type']])
                    ? $countsByType[$file['file_type']]+1
                    : 1;
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

    public function getOneFile(bool $asImage = false): string|null
    {
        $files = $this->getFilesInDB();
        if (!$files) {
            return null;
        }

        /** @var AttachFileEntity $model */
        $model = $files[0];
        $filePath = $model->file_path . DIRECTORY_SEPARATOR . implode('.', [$model->file_hash, $model->file_extension]);

        if ($asImage) {
            $photo = base64_encode(AttachFileHelper::readFromStorage($model->storage, $filePath));
            return "data:image/{$model->file_extension};base64,$photo";
        }

        return $filePath;
    }

    private function getFilesFromAllSource(string|null $type = null)
    {
        $files = $this->getFilesInDB($type);
        if (!$files) {
            $files = Yii::$app->getSession()->get(AttachFileHelper::getSessionKey($this->owner::class)) ?: [];
        }

        return $files;
    }

    private function getFilesInDB(string|null $type = null)
    {
        if ($this->filesInDB === null) {
            $this->filesInDB = AttachFileEntity::find()
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
