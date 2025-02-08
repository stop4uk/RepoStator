<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportDataEntity;

final class ReportDataFixture extends ActiveFixture
{
    public $modelClass = ReportDataEntity::class;
    public $depends = [
        ReportFixture::class,
        GroupFixture::class,
        ReportStructureFixture::class,
        UserFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reportsData.php';
        parent::beforeLoad();
    }
}