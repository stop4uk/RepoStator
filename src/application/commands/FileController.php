<?php

namespace app\commands;

use Yii;
use yii\console\{
    Controller,
    ExitCode
};
use yii\helpers\FileHelper;

use app\components\attachedFiles\AttachFileHelper;
use app\modules\reports\entities\{
    ReportFormTemplateEntity,
    ReportFormJobEntity
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\commands
 */
final class FileController extends Controller
{
    public function actionCleantempfilesfolder(): void
    {
        $filePath = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR . env('YII_FILES_TEMPORARY_PATH', 'tmpFiles');
        $files = FileHelper::findFiles($filePath);

        foreach($files as $file) {
            FileHelper::unlink($file);
        }

        exit(ExitCode::OK);
    }
    
    public function actionCleanformreports(): void
    {
        $workFiles = [];
        $templates = ReportFormTemplateEntity::find()->where(['form_usejobs' => 1])->with(['resultFiles'])->all();

        if (!$templates) {
            exit(ExitCode::OK);
        }

        foreach($templates as $template) {
            $workFiles[$template->id] = [
                'limit_maxfiles' => $template->limit_maxfiles,
                'limit_maxsavetime' => $template->limit_maxsavetime,
                'files' => $template->resultFiles
            ];
        }

        foreach($workFiles as $templateID => $workData) {
            if (
                $workData['limit_maxfiles']
                && count($workData['files']) > $workData['limit_maxfiles']
            ) {
                $files = $workFiles['files'];
                $filesToDelete = [];
                usort($files, fn($a, $b) => $b['updated_at'] <=> $a['updated_at']);

                while(count($files) > $workData['limit_maxfiles']) {
                    $filesToDelete[] = array_shift($files);
                }

                $workData['files'] = $files;
                foreach ($filesToDelete as $fDelete) {
                    AttachFileHelper::removeFromStorage(
                        storageID: $fDelete['storage'],
                        path: $fDelete['file_path'] . implode('.', [$fDelete['file_name'], $fDelete['file_extension']])
                    );

                    ReportFormJobEntity::deleteAll(['file_hash' => $fDelete['file_hash']]);
                }
            }

            if (
                $workData['limit_maxsavetime']
                && count($workData['files'])
            ) {
                foreach ($workData['files'] as $file) {
                    $diffTime = ((time() - $file['updated_at']) >= $workData['limit_maxsavetime']);
                    if ($diffTime) {
                        AttachFileHelper::removeFromStorage(
                            storageID: $file['storage'],
                            path: $file['file_path'] . implode('.', [$file['file_name'], $file['file_extension']])
                        );

                        ReportFormJobEntity::deleteAll(['file_hash' => $file['file_hash']]);
                    }
                }
            }
        }

        exit(ExitCode::OK);
    }
}