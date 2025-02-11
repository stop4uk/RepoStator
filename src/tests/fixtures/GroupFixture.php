<?php

namespace root\tests\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\GroupEntity;

final class GroupFixture extends ActiveFixture
{
    public $modelClass = GroupEntity::class;
    public $depends = [
        UserFixture::class,
        GroupTypeFixture::class,
        GroupNestedFixture::class
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'groups.php';
        parent::beforeLoad();
    }
}