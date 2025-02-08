<?php

namespace root\test\fixtures;

use Yii;
use yii\test\ActiveFixture;

use app\components\attachedFiles\AttachFileEntity;

final class AttachedFilesFixture extends ActiveFixture
{
    public $modelClass = AttachFileEntity::class;

    public function beforeLoad(): void
    {
        $this->dataFile = Yii::getAlias('@root/tests/_data') . DIRECTORY_SEPARATOR . 'attachedFiles.php';
        parent::beforeLoad();
    }
}