<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\UserRightEntity;

final class UserRightFixture extends ActiveFixture
{
    public $modelClass = UserRightEntity::class;

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'usersRights.php';
        parent::beforeLoad();
    }
}