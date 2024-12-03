<?php

namespace app\migrations;

use yii\db\Migration;

use app\components\attachfiles\AttachFileHelper;

class m241203_140718_attachFiles extends Migration
{
    const TABLE = '{{%attached_files}}';

    public function safeUp()
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
            'file_mime' => $this->string(30)->notNull(),
            'file_tags' => $this->json()->null()->defaultValue(null),
            'file_status' => $this->tinyInteger(1)->notNull()->defaultValue(AttachFileHelper::FSTATUS_ACTIVE),
            'file_version' => $this->integer(1)->notNull()->defaultValue(1),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->null()->defaultValue(null),
            'updated_at' => $this->integer(11)->null(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
