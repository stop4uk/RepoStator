<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104529_groupsDataInsert extends Migration
{
    const TABLE = '{{%groups}}';

    public function safeUp(): void
    {
        $this->batchInsert(self::TABLE, ["id", "code", "name", "name_full", "description", "accept_send", "type_id", "created_at", "created_uid", "updated_at", "updated_uid", "record_status"], [
            [
                'id' => 1,
                'code' => '001',
                'name' => 'Главная группа',
                'name_full' => null,
                'description' => null,
                'accept_send' => 0,
                'type_id' => null,
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
