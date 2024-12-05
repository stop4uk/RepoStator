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

        $model = AttachFileUploadForm::createFromParams($params);
        $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');
        if (!$model->modelKey) {
            $model->scenario = $model::SCENARIO_TEMPUPLOAD;
        }

        if ($model->validate()) {
            if (!$model->modelKey) {
                $path = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . env("YII_UPLOADS_PATH_TEMPPATH");
                $name = Yii::$app->getSecurity()->generateRandomString(6);
                $extension = pathinfo($model->uploadFile->name)['extension'];

                $fullName = implode('.', [$name, $extension]);
                $fullPath = $path . DIRECTORY_SEPARATOR . $fullName;

                if (!is_dir($path)) {mkdir($path);}
                $saveFile = $model->uploadFile->saveAs($fullPath);
                if ($saveFile) {
                    Yii::$app->getCache()->set('reportTempUpload_' . Yii::$app->getUser()->getId(), [
                        'path' => $path,
                        'fullPath' => $fullPath,
                        'name' => $fullName,
                        'extension' => $extension
                    ]);
                }

                return Json::encode([
                    'status' => $saveFile
                        ? 'success'
                        : 'error'
                ]);
            }

            $saveFile = $model->getWorkModel()->attachFile(
                inputFile: $model->uploadFile->tempName,
                type: $model->modelType,
                name: $model->uploadFile->name,
                extension: pathinfo($model->uploadFile->name)['extension']
            );

            if ($saveFile) {
                $result = ['status' => 'success'];
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
