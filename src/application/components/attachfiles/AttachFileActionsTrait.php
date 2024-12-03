<?php

namespace app\components\attachfiles;

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

        if ($model->validate()) {
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
        $object = Yii::createObject($paramsArray['modelClass']);

        $model = $object->find()->where([$object->modelKey => $paramsArray['modelKey']])->limit(1)->one();
        $model->detachFiles($paramsArray['hash']);
    }

    public function actionGetattachfile(string $params)
    {
        $paramsArray = unserialize(base64_decode($params));
        $object = Yii::createObject($paramsArray['modelClass']);
        $model = $object->find()->where([$object->modelKey => $paramsArray['modelKey']])->limit(1)->one();

        $fileData = $model->getAttachFile($paramsArray['hash']);
        if ( $fileData ) {
            return $this->response->sendContentAsFile($fileData['content'], $fileData['name'], [
                'inline' => false
            ]);
        }
    }
}
