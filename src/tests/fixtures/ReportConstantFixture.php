<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportConstantEntity;

final class ReportConstantFixture extends ActiveFixture
{
    public $modelClass = ReportConstantEntity::class;
    public $depends = [
        UserFixture::class,
        ReportFixture::class,
        GroupFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reportsConstant.php';
        parent::beforeLoad();
    }
}