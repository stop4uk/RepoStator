<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportDataChangeEntity;

final class ReportDataChangeFixture extends ActiveFixture
{
    public $modelClass = ReportDataChangeEntity::class;
    public $depends = [
        ReportFixture::class,
        ReportDataChangeEntity::class,
        UserFixture::class,
        GroupFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reportsDataChanges.php';
        parent::beforeLoad();
    }
}