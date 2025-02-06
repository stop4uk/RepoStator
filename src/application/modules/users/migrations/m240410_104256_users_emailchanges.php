<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104256_users_emailchanges extends Migration
{
    const TABLE = '{{%users_emailchanges}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->primaryKey(11)->unsigned(),
            'user_id'=> $this->integer(11)->notNull(),
            'email'=> $this->string(58)->notNull()->defaultValue(''),
            'key'=> $this->string(32)->notNull()->defaultValue(''),
            'created_at'=> $this->integer(11)->notNull(),
            'updated_at'=> $this->integer(11)->null()->defaultValue(null),
            'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
        ],$tableOptions);

        $this->createIndex('IDX_user_id',self::TABLE,['user_id'],false);
        $this->createIndex('IDX_email',self::TABLE,['email'],false);
        $this->createIndex('IDX_key',self::TABLE,['key'],false);
        $this->createIndex('IDX_record_status',self::TABLE,['record_status'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('IDX_user_id', self::TABLE);
        $this->dropIndex('IDX_email', self::TABLE);
        $this->dropIndex('IDX_key', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
