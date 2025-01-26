<?php

use yii\db\Migration;

final class m240410_104259_users_sessions extends Migration
{
    const TABLE = '{{%users_sessions}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->primaryKey(11)->unsigned(),
            'user_id'=> $this->integer(11)->notNull(),
            'ip'=> $this->string(45)->notNull()->defaultValue(''),
            'client'=> $this->string(255)->notNull()->defaultValue(''),
            'additional'=> $this->text()->null()->defaultValue(null),
            'created_at'=> $this->integer(11)->notNull(),
        ],$tableOptions);

        $this->createIndex('IDX_user_id',self::TABLE,['user_id'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('IDX_user_id', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
