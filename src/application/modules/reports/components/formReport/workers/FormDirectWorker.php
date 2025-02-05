<?php

namespace app\modules\reports\components\formReport\workers;

use Yii;

use app\modules\reports\components\formReport\base\BaseWorker;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\fomrReport\workers
 */
final class FormDirectWorker extends BaseWorker
{
    public function run()
    {
        $processor = $this->processor;
        $processor->form();

        $fileName = Yii::$app->getSecurity()->generateRandomString(5);
        $fileExtension = $processor->templateRecord['file_extension'] ?? 'xlsx';
        $fileMime = $processor->templateRecord['file_mime'] ?? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        $file = implode('.', [$fileName, $fileExtension]);
        header("Content-Type: $fileMime");
        header("Content-Disposition: attachment; filename=$file");
        header("Cache-Control: max-age=0");
        $processor->writer->save("php://output");
        die;
    }
}
