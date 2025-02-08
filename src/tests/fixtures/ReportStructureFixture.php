<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportStructureEntity;

final class ReportStructureFixture extends ActiveFixture
{
    public $modelClass = ReportStructureEntity::class;
    public $depends = [
        ReportFixture::class,
        GroupFixture::class,
        UserFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reportsStructures.php';
        parent::beforeLoad();
    }
}