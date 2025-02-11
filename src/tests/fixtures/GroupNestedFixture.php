<?php

namespace root\tests\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\GroupNestedEntity;

final class GroupNestedFixture extends ActiveFixture
{
    public $modelClass = GroupNestedEntity::class;
    public $depends = [
        GroupFixture::class
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'groupsNested.php';
        parent::beforeLoad();
    }
}