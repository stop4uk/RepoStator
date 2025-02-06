<?php

namespace app\modules\reports\components\formReport\jobs;

use Yii;
use yii\base\BaseObject;
use yii\base\ErrorException;
use yii\queue\JobInterface;

use app\components\attachedFiles\AttachFileHelper;
use app\modules\reports\{
    components\formReport\base\BaseProcessor,
    entities\ReportFormJobEntity
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\formReport\jobs
 */
final class FormTemplateJob extends BaseObject implements JobInterface
{
    public BaseProcessor $processor;
    public string $jobID;

    public function execute($queue): void
    {
        $processor = $this->processor;
        $processor->setJobID($this->jobID);
        $processor->form();

        $fileName = Yii::$app->getSecurity()->generateRandomString(12);
        $fileExtension = $processor->templateRecord['file_extension'] ?? 'xlsx';
        $fileMime = $processor->templateRecord['file_mime'] ?? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        $fileFullName =  implode('.', [$fileName, $fileExtension]);
        $filePath = Yii::getAlias('@runtime/'.env('YII_FILES_TEMPORARY_PATH', 'tmpFiles')) . DIRECTORY_SEPARATOR . $fileFullName;
        $processor->writer->save($filePath);

        $saveToStorage = AttachFileHelper::saveToStorage(
            Yii::$app->params['storageToSaveReportFiles'],
            $filePath,
            env('YII_DOWNLOADS_PATH_LOCAL', 'downloads'),
            $fileFullName
        );

        if ($saveToStorage) {
            $jobRecord = ReportFormJobEntity::find()->where(['job_id' => $this->jobID])->limit(1)->one();
            $jobRecord->setComplete(
                fileData: [
                    'storage' => Yii::$app->params['storageToSaveReportFiles'],
                    'file_name' => $fileName,
                    'file_hash' => Yii::$app->getSecurity()->generateRandomString(32),
                    'file_path' => env('YII_DOWNLOADS_PATH_LOCAL', 'downloads') . DIRECTORY_SEPARATOR,
                    'file_size' => filesize($filePath),
                    'file_extension' => $fileExtension,
                    'file_mime' => $fileMime,
                ],
                formPeriod: $processor->form->period
            );

            try {
                unlink($filePath);
            } catch(ErrorException $e) {}
        } else {
            Yii::error($saveToStorage);
        }
    }
}