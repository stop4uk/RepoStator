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

        $fileName = Yii::$app->getSecurity()->generateRandomString(5) . '.' . $processor->templateRecord['file_extension'];
        header("Content-Type: {$processor->templateRecord['file_mime']}");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Cache-Control: max-age=0");
        $this->writer->save("php://output");
        die;
    }
}
