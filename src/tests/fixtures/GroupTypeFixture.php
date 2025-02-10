<?php

namespace root\tests\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\GroupTypeEntity;

final class GroupTypeFixture extends ActiveFixture
{
    public $modelClass = GroupTypeEntity::class;
    public $depends = [
        UserFixture::class
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'groupsTypes.php';
        parent::beforeLoad();
    }
}