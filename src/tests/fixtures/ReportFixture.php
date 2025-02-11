<?php

namespace root\tests\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportEntity;

final class ReportFixture extends ActiveFixture
{
    public $modelClass = ReportEntity::class;
    public $depends = [
        UserFixture::class,
        GroupFixture::class
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reports.php';
        parent::beforeLoad();
    }
}