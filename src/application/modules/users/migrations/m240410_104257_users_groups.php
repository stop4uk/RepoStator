<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104257_users_groups extends Migration
{
    const TABLE = '{{%users_groups}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->primaryKey(11)->unsigned(),
            'user_id'=> $this->integer(11)->notNull(),
            'group_id'=> $this->integer(11)->notNull(),
            'created_at'=> $this->integer(11)->notNull(),
            'created_uid'=> $this->integer(11)->notNull(),
            'updated_at'=> $this->integer(11)->null()->defaultValue(null),
            'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
            'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
        ],$tableOptions);

        $this->createIndex('IDX_user_id',self::TABLE,['user_id'],false);
        $this->createIndex('IDX_group_id',self::TABLE,['group_id'],false);
        $this->createIndex('IDX_record_starus',self::TABLE,['record_status'],false);
        $this->createIndex('IDX_created_uid',self::TABLE,['created_uid'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('IDX_user_id', self::TABLE);
        $this->dropIndex('IDX_group_id', self::TABLE);
        $this->dropIndex('IDX_record_starus', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
