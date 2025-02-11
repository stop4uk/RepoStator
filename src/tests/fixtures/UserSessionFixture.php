<?php

namespace root\tests\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\modules\users\entities\UserSessionEntity;

final class UserSessionFixture extends ActiveFixture
{
    public $modelClass = UserSessionEntity::class;
    public $depends = [
        UserFixture::class,
    ];

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'usersSessions.php';
        parent::beforeLoad();
    }
}