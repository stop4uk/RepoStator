<?php

namespace root\tests\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\UserEmailchangeEntity;

final class UserEmailChangeFixture extends ActiveFixture
{
    public $modelClass = UserEmailchangeEntity::class;
    public $depends = [
        UserFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'usersEmailChanges.php';
        parent::beforeLoad();
    }
}