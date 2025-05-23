<?php

use yii\db\Migration;

use app\components\attachedFiles\AttachFileHelper;

final class m241203_140718_attachedFiles extends Migration
{
    const TABLE = '{{%attachedFiles}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'storage' => $this->string(255)->notNull()->defaultValue(AttachFileHelper::STORAGE_LOCAL),
            'name' => $this->string(255)->notNull(),
            'modelName' => $this->string(100)->notNull(),
            'modelKey' => $this->string(36)->notNull(),
            'file_type' => $this->string(255)->null()->defaultValue(null),
            'file_hash' => $this->string(32)->notNull(),
            'file_path' => $this->string(255)->notNull(),
            'file_size' => $this->bigInteger(20)->notNull(),
            'file_extension' => $this->string(4)->notNull(),
            'file_mime' => $this->string(255)->notNull(),
            'file_tags' => $this->json()->null()->defaultValue(null),
            'file_status' => $this->tinyInteger(1)->notNull()->defaultValue(AttachFileHelper::FSTATUS_ACTIVE),
            'file_version' => $this->integer(1)->notNull()->defaultValue(1),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->null()->defaultValue(null),
            'updated_at' => $this->integer(11)->null(),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
