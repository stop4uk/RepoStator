<?php

namespace root\tests\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\reports\entities\ReportConstantRuleEntity;

final class ReportConstantRuleFixture extends ActiveFixture
{
    public $modelClass = ReportConstantRuleEntity::class;
    public $depends = [
        UserFixture::class,
        ReportFixture::class,
        GroupFixture::class,
        ReportConstantFixture::class
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'reportsConstantRules.php';
        parent::beforeLoad();
    }
}