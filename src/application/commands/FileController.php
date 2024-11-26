<?php

namespace app\commands;

use app\helpers\CommonHelper;
use entities\{ReportFormTemplateEntity};
use entities\ReportFormJobEntity;
use Yii;
use yii\console\Controller;
use yii\helpers\{ArrayHelper, FileHelper};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\commands
 */
final class FileController extends Controller
{
    public function actionClean()
    {
        $templates = [];
        foreach (ReportFormTemplateEntity::find()->all() as $row) {
            if ( $row->limit_maxfiles ) {
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

        if ( !is_dir(Yii::getAlias(Yii::$app->params['downloadFormFilesAlias'])) ) {
            exit(0);
        }

        $files = FileHelper::findFiles(Yii::getAlias(Yii::$app->params['downloadFormFilesAlias']));
        if ( !count($files) || !$templates || !$jobs ) {
            exit(0);
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

                if ( $fTimes ) {
                    ksort($fTimes);
                    $count = $countFiles - $jobs[$templateID];
                    foreach ($fTimes as $time => $elements) {
                        foreach($elements as $saveFile) {
                            if ( $count > 0) {
                                if ( CommonHelper::deleteFileAttempt($saveFile) ) {
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

                    if ( $diffTime ) {
                        if ( CommonHelper::deleteFileAttempt($file) ) {
                            ReportFormJobEntity::deleteAll(['id' => $jobsForDelete[$saveFile]]);
                        }
                    }
                }
            }
        }

        exit(0);
    }
}