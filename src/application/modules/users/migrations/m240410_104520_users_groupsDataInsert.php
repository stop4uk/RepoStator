<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104520_users_groupsDataInsert extends Migration
{
    const TABLE = '{{%users_groups}}';

    public function safeUp(): void
    {
        $this->batchInsert('{{%users_groups}}', ["id", "user_id", "group_id", "created_at", "created_uid", "updated_at", "updated_uid", "record_status"], [
            [
                'id' => 1,
                'user_id' => 1,
                'group_id' => 1,
                'created_at' => time(),
                'created_uid' => 1,
                'updated_at' => null,
                'updated_uid' => null,
                'record_status' => 1,
            ],
        ]);
    }

    public function safeDown(): void
    {
        $this->truncateTable(self::TABLE . ' CASCADE');
    }
}
