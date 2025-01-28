<?php

use yii\db\Migration;

final class m240410_104521_users_rightsDataInsert extends Migration
{
    const TABLE = '{{%users_rights}}';

    public function safeUp(): void
    {
        $this->batchInsert(self::TABLE, ["item_name", "user_id", "created_at", "created_uid"], [
            [
                'item_name' => 'admin',
                'user_id' => 1,
                'created_at' => time(),
                'created_uid' => 1,
            ],
        ]);
    }

    public function safeDown(): void
    {
        $this->truncateTable(self::TABLE . ' CASCADE');
    }
}
