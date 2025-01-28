<?php

namespace app\components\attachedFiles;

use Yii;
use yii\base\Exception;
use yii\web\{
    Response,
    UploadedFile
};
use yii\helpers\{
    FileHelper,
    Json
};

trait AttachFileActionsTrait
{
    private string $sessionKey;
    private string $temporaryPath;

    public function init(): void
    {
        $this->sessionKey = env('YII_UPLOADS_TEMPORARY_KEY', 'tmpUploadSession') . $this->getUserID();
        $this->temporaryPath = Yii::getAlias('@runtime/' . env("YII_UPLOADS_TEMPORARY_PATH", 'tmpUpload'));

        parent::init();
    }

    public function actionAttachfile(string $params): string
    {
        $result = [];
        $session = Yii::$app->getSession();
        $temporaryName = Yii::$app->getSecurity()->generateRandomString(6);

        $model = AttachFileUploadForm::createFromParams($params);
        $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');
        if ($model->isNewRecord) {
            $model->scenario = $model::SCENARIO_TEMPUPLOAD;
        }

        if ($model->validate()) {
            if ($model->isNewRecord) {
                $sessionFiles = $session->get($this->sessionKey) ?: [];
                $fileExtension = pathinfo($model->uploadFile->name)['extension'];
                $fileName = implode('.', [$temporaryName, $fileExtension]);
                $filePath = $this->temporaryPath . DIRECTORY_SEPARATOR . $fileName;

                if (!is_dir($this->temporaryPath)) {
                    mkdir($this->temporaryPath);
                }

                $file = $model->uploadFile->saveAs($filePath);
                if ($file) {
                    $sessionFiles[$fileName] = [
                        'path' => $this->temporaryPath,
                        'fullPath' => $filePath,
                        'name' => $fileName,
                        'extension' => $fileExtension,
                        'file_type' => $model->modelType
                            ?: array_key_first($model->getWorkModel()->attachRules)
                    ];

                    $session->set($this->sessionKey, $sessionFiles);
                }

                return Json::encode([
                    'status' => $file
                        ? 'success'
                        : 'error',
                    'isNewRecord' => $model->isNewRecord,
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
                    'isNewRecord' => $model->isNewRecord,
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

        if (!$paramsArray['modelKey']) {
            $session = Yii::$app->getSession();
            $filePath = $this->temporaryPath . DIRECTORY_SEPARATOR . $params['hash'];
            $sessionFiles = $session->get($this->sessionKey);

            if ($sessionFiles) {
                foreach($sessionFiles as $key => $file) {
                    if ($file['name'] == $params['hash']) {
                        unset($sessionFiles[$key]);
                        if (is_file($filePath)) {
                            try{
                                FileHelper::unlink($filePath);
                            } catch (Exception $e) {}
                        }
                    }
                }

                $session->set($this->sessionKey, $sessionFiles);
            }

            return;
        }

        $object = Yii::createObject($paramsArray['modelClass']);
        $model = $object->find()->where([$object->modelKey => $paramsArray['modelKey']])->limit(1)->one();
        $model->detachFiles($paramsArray['hash']);
    }

    public function actionGetfile(string $params): Response|array
    {
        $paramsArray = unserialize(base64_decode($params));
        $fileData = [];

        if (!$paramsArray['modelKey']) {
            $session = Yii::$app->getSession();
            $sessionFiles = $session->get($this->sessionKey);

            if ($sessionFiles) {
                $filePath = $this->temporaryPath . DIRECTORY_SEPARATOR . $paramsArray['hash'];
                if (is_file($filePath)) {
                    $fileData = [
                        'content' => file_get_contents($filePath),
                        'name' => $paramsArray['hash']
                    ];
                }
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

    private function getUserID(): int
    {
        try {
            return Yii::$app->getUser()->getId();
        } catch(Exception $e) {}

        return 0;
    }
}
