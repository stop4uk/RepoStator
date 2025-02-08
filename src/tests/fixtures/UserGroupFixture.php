<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\UserGroupEntity;

final class UserGroupFixture extends ActiveFixture
{
    public $modelClass = UserGroupEntity::class;
    public $depends = [
        GroupFixture::class
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'usersGroups.php';
        parent::beforeLoad();
    }
}