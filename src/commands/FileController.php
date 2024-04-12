<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\{
    ArrayHelper,
    FileHelper
};

use app\entities\report\{
    ReportFormTemplateEntity,
    ReportFormJobEntity
};

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

        $jobs = ArrayHelper::map(ReportFormJobEntity::find()
            ->where(['job_status' => ReportFormJobEntity::STATUS_COMPLETE])
            ->all(), 'id', 'file', 'template_id');

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
                    $fTimes[filemtime($file)][] = $file;
                }

                if ( $fTimes ) {
                    ksort($fTimes);
                    $count = $countFiles - $jobs[$templateID];
                    foreach ($fTimes as $time => $elements) {
                        foreach($elements as $saveFile) {
                            if ( $count > 0) {
                                FileHelper::unlink($saveFile);
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
                    $createdTime = filemtime($file);
                    $diffTime = ((time() - $createdTime) <= $maxSaveTime);

                    if ( $diffTime ) {
                        FileHelper::unlink($file);
                    }
                }
            }
        }

        exit(0);
    }
}