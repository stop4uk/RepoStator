<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\UserEmailchangeEntity;

final class UserEmailChangeFixture extends ActiveFixture
{
    public $modelClass = UserEntity::class;
    public $depends = [
        UserFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'usersEmailChanges.php';
        parent::beforeLoad();
    }
}