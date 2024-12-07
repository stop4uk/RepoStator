<?php

namespace app\components\attachedFiles;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Json;

trait AttachFileActionsTrait
{
    public function actionAttachfile(string $params): string
    {
        $result = [];
        $temporaryPath = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . env("YII_UPLOADS_PATH_TEMPPATH");
        $temporaryName = Yii::$app->getSecurity()->generateRandomString(6);


        $model = AttachFileUploadForm::createFromParams($params);
        $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');
        if ($model->isNewRecord) {
            $model->scenario = $model::SCENARIO_TEMPUPLOAD;
        }

        if ($model->validate()) {
            if ($model->isNewRecord) {
                $fileExtension = pathinfo($model->uploadFile->name)['extension'];
                $fileName = implode('.', [$temporaryName, $fileExtension]);
                $filePath = $temporaryPath . DIRECTORY_SEPARATOR . $fileName;

                if (!is_dir($temporaryPath)) {mkdir($temporaryPath);}
                $saveFile = $model->uploadFile->saveAs($filePath);
                if ($saveFile) {
                    Yii::$app->getCache()->set('reportTempUpload_' . Yii::$app->getUser()->getId(), [
                        'path' => $temporaryPath,
                        'fullPath' => $filePath,
                        'name' => $fileName,
                        'extension' => $fileExtension
                    ]);
                }

                return Json::encode([
                    'status' => $saveFile
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
            return;
        }

        $object = Yii::createObject($paramsArray['modelClass']);

        $model = $object->find()->where([$object->modelKey => $paramsArray['modelKey']])->limit(1)->one();
        $model->detachFiles($paramsArray['hash']);
    }

    public function actionGetfile(string $params)
    {
        $paramsArray = unserialize(base64_decode($params));
        if (!$paramsArray['modelKey']) {
            return;
        }

        $object = Yii::createObject($paramsArray['modelClass']);
        $model = $object
            ->find()
            ->where([$object->modelKey => $paramsArray['modelKey']])
            ->limit(1)
            ->one();

        $fileData = $model->getAttachFile($paramsArray['hash']);
        if ( $fileData ) {
            return $this->response->sendContentAsFile($fileData['content'], $fileData['name'], [
                'inline' => false
            ]);
        }
    }
}
