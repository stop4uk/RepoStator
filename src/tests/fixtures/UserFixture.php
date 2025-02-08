<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\UserEntity;

final class UserFixture extends ActiveFixture
{
    public $modelClass = UserEntity::class;
    public $depends = [
        UserGroupFixture::class,
        UserRightFixture::class,
        UserEmailChangeFixture::class,
        UserSessionFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'users.php';
        parent::beforeLoad();
    }
}