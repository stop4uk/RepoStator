<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\{
    ArrayHelper,
    FileHelper
};

use app\helpers\CommonHelper;
use app\modules\reports\entities\{
    ReportFormTemplateEntity,
    ReportFormJobEntity
};
use yii\console\ExitCode;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\commands
 */
final class FileController extends Controller
{
    public function cleanTemporaryFolder()
    {
        $filePath = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . env('YII_FILES_TEMPORARY_PATH', 'tmpFiles');
        $files = FileHelper::findFiles($filePath);

        foreach($files as $file) {
            FileHelper::unlink($file);
        }

        exit(ExitCode::OK);
    }
    
    public function actionClean()
    {
        $templates = [];
        foreach (ReportFormTemplateEntity::find()->all() as $row) {
            if ($row->limit_maxfiles) {
                $templates[$row->id] = [
                    'limit_maxfiles' => $row->limit_maxfiles,
                    'limit_maxsavetime' => $row->limit_maxsavetime
                ];
            }
        }

        $queryJobs = ReportFormJobEntity::find()
            ->where(['job_status' => ReportFormJobEntity::STATUS_COMPLETE])
            ->all();

        $jobs = ArrayHelper::map($queryJobs, 'id', 'file', 'template_id');
        $jobsForDelete = ArrayHelper::map($queryJobs, 'file', 'id');

        if (!is_dir(Yii::getAlias(Yii::$app->params['downloadFormFilesAlias']))) {
            exit(ExitCode::OK);
        }

        $files = FileHelper::findFiles(Yii::getAlias(Yii::$app->params['downloadFormFilesAlias']));
        if (!count($files) || !$templates || !$jobs) {
            exit(ExitCode::OK);
        }

        foreach ($templates as $templateID => $params) {
            $countFiles = $jobs[$templateID];

            if (
                isset($jobs[$templateID])
                && $countFiles > $params['limit_maxfiles']
            ) {
                $fTimes = [];
                foreach ($jobs[$templateID] as $file) {
                    $fTimes[Yii::getAlias(filemtime($file))][] = $file;
                }

                if ($fTimes) {
                    ksort($fTimes);
                    $count = $countFiles - $jobs[$templateID];
                    foreach ($fTimes as $time => $elements) {
                        foreach($elements as $saveFile) {
                            if ($count > 0) {
                                if (CommonHelper::deleteFileAttempt($saveFile)) {
                                    ReportFormJobEntity::deleteAll(['id' => $jobsForDelete[$saveFile]]);
                                }
                                $count--;
                            }

                            break;
                        }
                    }
                }

            }

            foreach ($jobs as $templateID => $files) {
                $maxSaveTime = $templates[$templateID];
                foreach ($files as $file) {
                    $createdTime = Yii::getAlias(filemtime($file));
                    $diffTime = ((time() - $createdTime) <= $maxSaveTime);

                    if ($diffTime) {
                        if (CommonHelper::deleteFileAttempt($file)) {
                            ReportFormJobEntity::deleteAll(['id' => $jobsForDelete[$saveFile]]);
                        }
                    }
                }
            }
        }

        exit(ExitCode::OK);
    }
}