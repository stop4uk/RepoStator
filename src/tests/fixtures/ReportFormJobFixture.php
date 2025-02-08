<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportFormJobEntity;

final class ReportFormJobFixture extends ActiveFixture
{
    public $modelClass = ReportFormJobEntity::class;
    public $depends = [
        ReportFixture::class,
        ReportFormTemplateFixture::class,
        UserFixture::class,
        GroupFixture::class
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reportsFormJobs.php';
        parent::beforeLoad();
    }
}