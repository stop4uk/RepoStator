<?php

namespace app\components\attachedFiles;

use Yii;
use yii\base\ErrorException;
use yii\web\{
    Response,
    UploadedFile
};
use yii\helpers\{
    FileHelper,
    Json
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\attachedFiles
 */
trait AttachFileActionsTrait
{
    private string $temporaryPath;

    public function init(): void
    {
        $this->temporaryPath = Yii::getAlias('@runtime/' . env("YII_FILES_TEMPORARY_PATH", 'tmpFiles'));

        parent::init();
    }

    public function actionAttachfile(string $params): string
    {
        $result = [];
        $session = Yii::$app->getSession();
        $temporaryName = Yii::$app->getSecurity()->generateRandomString(6);

        $model = AttachFileUploadForm::createFromParams($params);
        $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');
        if ($model->loadInSession) {
            $model->scenario = $model::SCENARIO_TEMPUPLOAD;
        }

        $loadInSession = ($model->scenario == $model::SCENARIO_TEMPUPLOAD);
        if ($model->validate()) {
            if ($model->scenario == $model::SCENARIO_TEMPUPLOAD) {
                $sessionKey = AttachFileHelper::getSessionKey($model->getWorkModel()::class);
                $sessionFiles = $session->get($sessionKey) ?: [];

                $fileExtension = pathinfo($model->uploadFile->name)['extension'];
                $fileName = implode('.', [$temporaryName, $fileExtension]);
                $filePath = $this->temporaryPath . DIRECTORY_SEPARATOR . $fileName;

                if (!is_dir($this->temporaryPath)) {
                    mkdir($this->temporaryPath);
                }

                $file = $model->uploadFile->saveAs($filePath);
                $fileSize = filesize($filePath);
                $fileMime = FileHelper::getMimeType($filePath);

                if ($file) {
                    $sessionFiles[$temporaryName] = [
                        'name' => $model->uploadFile->name,
                        'file_type' => $model->modelType
                            ?: 'default',
                        'file_hash' => $temporaryName,
                        'file_path' => $filePath,
                        'file_size' => $fileSize,
                        'file_extension' => $fileExtension,
                        'file_mime' => $fileMime,
                        'file_tags' => $model->getWorkModel()->attachRules[$model->modelType]['tags'] ?? null,
                        'file_version' => null,
                        'created_uid' => Yii::$app->getUser()->getId(),
                    ];

                    $session->set($sessionKey, $sessionFiles);
                }

                return Json::encode([
                    'status' => $file
                        ? 'success'
                        : 'error',
                    'loadInSession' => $loadInSession,
                ]);
            }

            $saveFile = $model->getWorkModel()->attachFile(
                inputFile: $model->uploadFile->tempName,
                type: $model->modelType,
                name: $model->uploadFile->name,
                extension: pathinfo($model->uploadFile->name)['extension']
            );

            if ($saveFile) {
                $result = [
                    'status' => 'success',
                    'loadInSession' => $loadInSession,
                ];
            }

        } else {
            $result = [
                'status' => 'error',
                'errors' => $model->errors
            ];
        }

        return Json::encode($result);
    }

    public function actionDetachfile(string $params): void
    {
        $paramsArray = unserialize(base64_decode($params));
        $session = Yii::$app->getSession();
        $sessionKey = AttachFileHelper::getSessionKey($paramsArray['modelClass']);
        $sessionFiles = $session->get($sessionKey);

        if (
            $sessionFiles
            && isset($sessionFiles[$paramsArray['hash']])
        ) {
            $filePath = $this->temporaryPath . DIRECTORY_SEPARATOR . implode('.', [$paramsArray['hash'], $sessionFiles[$paramsArray['hash']]['file_extension']]);
            try{
                FileHelper::unlink($filePath);
            } catch (ErrorException $e) {}

            try {
                unset($sessionFiles[$paramsArray['hash']]);
            } catch (ErrorException $e) {}

            $session->set($sessionKey, $sessionFiles);

            return;
        }

        $object = Yii::createObject($paramsArray['modelClass']);
        $model = $object->find()->where([$object->modelKey => $paramsArray['modelKey']])->limit(1)->one();
        $model->detachFiles($paramsArray['hash']);
    }

    public function actionGetfile(string $params): Response|array
    {
        $paramsArray = unserialize(base64_decode($params));
        $session = Yii::$app->getSession();
        $sessionKey = AttachFileHelper::getSessionKey($paramsArray['modelClass']);
        $sessionFiles = $session->get($sessionKey);

        if (
            $sessionFiles
            && isset($sessionFiles[$paramsArray['hash']])
        ) {
            $fName = implode('.', [$paramsArray['hash'], $sessionFiles[$paramsArray['hash']]['file_extension']]);

            $filePath = $this->temporaryPath . DIRECTORY_SEPARATOR . $fName;
            if (is_file($filePath)) {
                $fileData = [
                    'content' => file_get_contents($filePath),
                    'name' => $fName
                ];
            }
        } else {
            $object = Yii::createObject($paramsArray['modelClass']);
            $model = $object
                ->find()
                ->where([$object->modelKey => $paramsArray['modelKey']])
                ->limit(1)
                ->one();

            $fileData = $model->getAttachFile($paramsArray['hash']);
        }

        return $fileData
            ? $this->response->sendContentAsFile($fileData['content'], $fileData['name'], ['inline' => false])
            : [];
    }

    public function actionGetfiledirect(string $params): Response
    {
        $paramsArray = unserialize(base64_decode($params));
        $fileData = AttachFileHelper::readStreamFromStorage(
            storageID: $paramsArray['storageID'],
            pathToFile: $paramsArray['pathToFile']
        );

        return $this->response->sendContentAsFile($fileData, $paramsArray['fileName'], ['inline' => false]);
    }
}
