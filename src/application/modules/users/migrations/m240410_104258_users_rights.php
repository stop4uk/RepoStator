<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104258_users_rights extends Migration
{
    const TABLE = '{{%users_rights}}';

    public function safeUp():void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'item_name'=> $this->string(64)->notNull(),
            'user_id'=> $this->integer(11)->notNull(),
            'created_at'=> $this->integer(11)->notNull(),
            'created_uid'=> $this->integer(11)->notNull(),
        ],$tableOptions);

        $this->createIndex('IDX_user_id',self::TABLE,['user_id'],false);
        $this->addPrimaryKey('pk_on_users_rights',self::TABLE,['item_name','user_id']);

    }

    public function safeDown(): void
    {
        $this->dropPrimaryKey('pk_on_users_rights',self::TABLE);
        $this->dropIndex('IDX_user_id', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
