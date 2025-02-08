<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportFormTemplateEntity;

final class ReportFormTemplateFixture extends ActiveFixture
{
    public $modelClass = ReportFormTemplateEntity::class;
    public $depends = [
        ReportFixture::class,
        UserFixture::class,
        GroupFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reportsFormTemplates.php';
        parent::beforeLoad();
    }
}